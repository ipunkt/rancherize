<?php namespace Rancherize\RancherAccess;
use Rancherize\RancherAccess\ApiService\ApiService;
use Rancherize\RancherAccess\Exceptions\MultipleActiveServicesException;
use Rancherize\RancherAccess\Exceptions\NoActiveServiceException;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use Rancherize\Services\ProcessTrait;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Yaml\Yaml;
use ZipArchive;

/**
 * Class RancherService
 * @package Rancherize\RancherAccess
 */
class RancherService {
	/**
	 * @var RancherAccount
	 */
	private $account;
	/**
	 * @var ApiService
	 */
	private $apiService;

	use ProcessTrait;


	/**
	 * RancherService constructor.
	 * @param ApiService $apiService
	 * @param ProcessHelper $processHelper
	 * @param RancherAccount $account
	 */
	public function __construct(ApiService $apiService, RancherAccount $account = null) {
		$this->account = $account;
		$this->apiService = $apiService;
	}

	/**
	 * @param RancherAccount $account
	 */
	public function setAccount(RancherAccount $account) {
		$this->account = $account;
		return $this;
	}

	/**
	 * @param string $stackName
	 * @return string
	 */
	public function retrieveConfig(string $stackName) : array {

		$stackId = $this->getStackIdByName($stackName);

		$url = implode('/', [
			$this->account->getUrl(),
			'environments',
			$stackId,
			'?action=exportconfig'
		]);

		$headers = [];
		$this->addAuthHeader($headers);

		$jsonContent = json_encode([
			'serviceIds' => []
		]);
		$data = $this->apiService->post($url, $jsonContent, $headers, [200]);

		$decodedData = json_decode($data, true);
		$dockerCompose = $decodedData['dockerComposeConfig'];
		$rancherCompose = $decodedData['rancherComposeConfig'];

		// Empty files are not sent empty so we force them to be
		if(substr($dockerCompose, 0, 2) === '{}')
			$dockerCompose = '';
		if(substr($rancherCompose, 0, 2) === '{}')
			$rancherCompose = '';

		return [$dockerCompose, $rancherCompose];
	}

	/**
	 * @param $stackName
	 * @return string
	 */
	private function getStackIdByName($stackName) {
		$url = implode('/', [
			$this->account->getUrl(),
			'environments'
		]);

		$headers = [];
		$this->addAuthHeader($headers);

		$jsonData = $this->apiService->get($url, $headers);
		$data = json_decode($jsonData, true);

		foreach($data['data'] as $stack) {
			if(strtolower($stack['name']) === strtolower($stackName) )
				return $stack['id'];
		}

		throw new StackNotFoundException($stackName, 11);
	}

	/**
	 * @param $headers
	 */
	protected function addAuthHeader(&$headers) {
		$user = $this->account->getKey();
		$password = $this->account->getSecret();

		$headers['Authorization'] = 'Basic ' . base64_encode("$user:$password");
	}

	/**
	 * @param string $stackName
	 */
	public function createStack(string $stackName, $dockerCompose = null, $rancherCompose = null) {
		if($dockerCompose === null)
			$dockerCompose = '';
		if($rancherCompose === null)
			$rancherCompose = '';


		$url = implode('/', [
			$this->account->getUrl(),
			'environments'
		]);

		$headers = [];
		$this->addAuthHeader($headers);

		$jsonContent = json_encode([
			'name' => $stackName,
			'dockerCompose' => $dockerCompose,
			'rancherCompose' => $rancherCompose,
		]);

		$headers['Content-Type'] = 'application/json';
		$headers['Content-Length'] = strlen($jsonContent);

		$this->apiService->post($url, $jsonContent, $headers);
	}

	/**
	 * @param string $directory
	 * @param string $stackName
	 */
	public function start(string $directory, string $stackName) {

		$process = ProcessBuilder::create([
			'rancher-compose', "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName, 'up', '-d'
		])
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $this->account->getUrl(),
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
	}

	/**
	 * @param string $directory
	 * @param string $stackName
	 * @param string $activeService
	 * @param string $replacementService
	 */
	public function upgrade(string $directory, string $stackName, string $activeService, string $replacementService) {

		$baseCommand = [
			'rancher-compose', "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName
		];

		$commands = [
			'upgrade' => array_merge($baseCommand, ['upgrade', '-w', '-c', $activeService, $replacementService]),
			'up' => array_merge($baseCommand, ['up', '-d', '-c', '--force-upgrade']),
		];

		$usedCommand = 'upgrade';
		if($activeService === $replacementService)
			$usedCommand = 'up';


		$process = ProcessBuilder::create( $commands[$usedCommand] )
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $this->account->getUrl(),
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
	}

	/**
	 * Looks for the active service in $stackName which is not a Sidekick and contains $name in it.
	 * TODO: switch from checking the docker-compose.yml and rancher-compose.yml to api access
	 *
	 * @param string $stackName
	 * @param string $name
	 * @return string
	 */
	public function getActiveService(string $stackName, string $name) : string {

		list($dockerConfig, $rancherConfig) = $this->retrieveConfig($stackName);

		$dockerData = Yaml::parse($dockerConfig);
		$rancherData = Yaml::parse($rancherConfig);

		if( !is_array($dockerData) || !is_array($rancherData) )
			throw new NoActiveServiceException($name);

		$sidekicks = [];
		foreach($dockerData as $serviceName => $data) {

			if( !array_key_exists('labels', $data) )
				continue;

			if( !array_key_exists('io.rancher.sidekicks', $data['labels']) )
				continue;

			$serviceSidekicks = explode(',', $data['labels']['io.rancher.sidekicks']);
			$translatedServiceSidekicks = [];
			foreach($serviceSidekicks as $sidekick)
				$translatedServiceSidekicks[] = trim(strtolower($sidekick));
			$sidekicks = array_merge($sidekicks, $translatedServiceSidekicks);
		}

		$matchingServices = [];
		foreach($rancherData as $serviceName => $data) {

			$translatedServiceName = trim(strtolower($serviceName));

			if( in_array($translatedServiceName, $sidekicks) )
				continue;

			$serviceNameContainsName = strpos($serviceName, $name) !== false;
			if( !$serviceNameContainsName )
				continue;

			if(!array_key_exists('scale', $data))
				continue;

			$containerIsActive = $data['scale'] > 0;
			if( !$containerIsActive )
				continue;

			$matchingServices[] = $serviceName;
		}

		if( 1 < count($matchingServices) )
			throw new MultipleActiveServicesException($name, $matchingServices);

		if( empty($matchingServices))
			throw new NoActiveServiceException($name);

		return reset($matchingServices);

	}
}