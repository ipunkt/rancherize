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
 *
 * This service handles all requests to the rancher orchestration server
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
	 * @param RancherAccount $account
	 */
	public function __construct(ApiService $apiService, RancherAccount $account = null) {
		$this->account = $account;
		$this->apiService = $apiService;
	}

	/**
	 * Set the account the be used for all requests
	 *
	 * @param RancherAccount $account
	 * @return $this
	 */
	public function setAccount(RancherAccount $account) {
		$this->account = $account;
		return $this;
	}

	/**
	 * Retrieve the docker-compose.yml and rancher-compose.yml for the given stack
	 *
	 * @param string $stackName
	 * @return array
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
	 * Translate the given Stackname into an id.
	 * Throws StackNotFoundException if no matching stack was found
	 *
	 * @param $stackName
	 * @return string
	 * @throws StackNotFoundException
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
	 * Helper function to add the basic auth header required to access the api to the array of headers provided
	 *
	 * @param $headers
	 */
	protected function addAuthHeader(&$headers) {
		$user = $this->account->getKey();
		$password = $this->account->getSecret();

		$headers['Authorization'] = 'Basic ' . base64_encode("$user:$password");
	}

	/**
	 * Prompt Rnacher to create a stack with the given name using the provided dockerCompose and rancherCompose files
	 *
	 * @param string $stackName
	 * @param null $dockerCompose
	 * @param null $rancherCompose
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
	 * Start the currently built configuration inside the given rancher stack
	 *
	 * @param string $directory
	 * @param string $stackName
	 */
	public function start(string $directory, string $stackName) {

		$process = ProcessBuilder::create([
			$this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName, 'up', '-d'
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
	 * Upgrade the given activeService to the replacementService within the given stack, using the currently built
	 * configuration in directory
	 *
	 * @param string $directory
	 * @param string $stackName
	 * @param string $activeService
	 * @param string $replacementService
	 */
	public function upgrade(string $directory, string $stackName, string $activeService, string $replacementService) {

		$baseCommand = [
			$this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName
		];

		$commands = [
			'upgrade' => array_merge($baseCommand, ['upgrade', '-w', '-c', $activeService, $replacementService]),
			'up' => array_merge($baseCommand, ['up', '-d', '-c']),
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
	 * Look for the active service in $stackName which is not a Sidekick and contains $name in it.
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

		// primitive way of handling docker-compose.yml version 2
		if( array_key_exists('version', $dockerData) && $dockerData['version'] == 2)
			$dockerData = $dockerData['services'];

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

	/**
	 * @param string $stackName
	 * @param string $serviceName
	 * @return string
	 */
	public function getCurrentVersion(string $stackName, string $serviceName) {
		$currentService = $this->getActiveService($stackName, $serviceName);

		return substr($currentService, strlen($serviceName.'-') );
	}
}