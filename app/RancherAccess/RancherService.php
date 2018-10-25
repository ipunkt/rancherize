<?php namespace Rancherize\RancherAccess;
use Rancherize\RancherAccess\ApiService\ApiService;
use Rancherize\RancherAccess\Exceptions\ConfirmFailedException;
use Rancherize\RancherAccess\Exceptions\CreateFailedException;
use Rancherize\RancherAccess\Exceptions\MissingDataException;
use Rancherize\RancherAccess\Exceptions\MultipleActiveServicesException;
use Rancherize\RancherAccess\Exceptions\NameNotFoundException;
use Rancherize\RancherAccess\Exceptions\NoActiveServiceException;
use Rancherize\RancherAccess\Exceptions\RemoveFailedException;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use Rancherize\RancherAccess\Exceptions\StartFailedException;
use Rancherize\RancherAccess\Exceptions\StopFailedException;
use Rancherize\RancherAccess\Exceptions\UpgradeFailedException;
use Rancherize\RancherAccess\NameMatcher\NameMatcher;
use Rancherize\Services\ProcessTrait;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Yaml\Yaml;

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

	/**
	 * @var
	 */
	private $byNameService;

	/**
	 * @var bool
	 */
	private $cliMode = false;

	use ProcessTrait;


	/**
	 * RancherService constructor.
	 * @param ApiService $apiService
	 * @param RancherAccount $account
	 */
	public function __construct(ApiService $apiService, RancherAccount $account = null) {
		$this->account = $account;
		$this->apiService = $apiService;
		$this->byNameService = new ByNameService();
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
		if( !array_key_exists('data', $data) )
			throw new MissingDataException('data', array_keys($data) );
		$stacks = $data['data'];

		try {
			$stack = $this->byNameService->findName($stacks, $stackName);
			return $stack['id'];
		} catch(NameNotFoundException $e) {
			throw new StackNotFoundException($stackName, 11);
		}

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
	 * TODO: Move to RancherComposeVersion interface
	 */
	protected function getUrl() {
		$url = $this->account->getUrl();
		$version = $this->account->getComposeVersion();

		if($version === '0.9')
			return $url;

		// Version 0.10, current
		$matches = [];
		if( !preg_match('~(http[s]?://.*?/)~', $url, $matches) )
			throw new UrlConversionFailedException($url, '0.10');

		return $matches[0];
	}

	/**
	 * Start the currently built configuration inside the given rancher stack
	 *
	 * @param string $directory
	 * @param string $stackName
	 * @param array $serviceNames only start a certain service
	 * @param bool $upgrade
	 * @param bool $forcedUpgrade
	 */
	public function start(string $directory, string $stackName, array $serviceNames = null, bool $upgrade = false, bool $forcedUpgrade = false) {
		if($serviceNames === null)
			$serviceNames = [];
		if( !is_array($serviceNames) )
			$serviceNames = [$serviceNames];

		$url = $this->getUrl();
		$account = $this->account;
		if ($this->cliMode && $account instanceof RancherCliAccount)
            $command = [
                $account->getCliVersion(),
                'up',
                "-f",
                "$directory/docker-compose.yml",
                '--rancher-file',
                "$directory/rancher-compose.yml",
                '-s',
                $stackName,
                '-d',
                '-p'
            ];
		else
            $command = [
                $account->getRancherCompose(),
                "-f",
                "$directory/docker-compose.yml",
                '-r',
                "$directory/rancher-compose.yml",
                '-p',
                $stackName,
                'up',
                '-d',
                '-p'
            ];

		if($upgrade)
			$command = array_merge($command, ['--upgrade']);

		if($forcedUpgrade)
			$command = array_merge($command, ['--force-upgrade']);

		$command = array_merge($command, $serviceNames);

		$process = ProcessBuilder::create( $command )
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new StartFailedException("Command ".$ran->getCommandLine());
	}

	/**
	 * Confirm an in-service upgrade
	 *
	 * @param string $directory
	 * @param string $stackName
	 * @param array $serviceNames only start a certain service
	 */
	public function confirm(string $directory, string $stackName, array $serviceNames = null) {
		if($serviceNames === null)
			$serviceNames = [];
		if( !is_array($serviceNames) )
			$serviceNames = [$serviceNames];

		$url = $this->getUrl();
		$command = [
			$this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r',
			"$directory/rancher-compose.yml", '-p', $stackName, 'up', '-d', '--confirm-upgrade'
		];

		$command = array_merge($command, $serviceNames);

		$process = ProcessBuilder::create( $command )
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new ConfirmFailedException("Command ".$ran->getCommandLine());
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


		$url = $this->getUrl();
		$process = ProcessBuilder::create( $commands[$usedCommand] )
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new UpgradeFailedException("Command ".$ran->getCommandLine());
	}

	/**
	 * Look for the active service in $stackName which is not a Sidekick and contains $name in it.
	 * TODO: switch from checking the docker-compose.yml and rancher-compose.yml to api access
	 *
	 * @param string $stackName
	 * @param NameMatcher $nameMatcher
	 * @return array
	 * @internal param string $name
	 */
	public function getActiveServiceDefinition(string $stackName, NameMatcher $nameMatcher) : array {

		list($dockerConfig, $rancherConfig) = $this->retrieveConfig($stackName);

		$dockerData = Yaml::parse($dockerConfig);
		$rancherData = Yaml::parse($rancherConfig);

		if( !is_array($dockerData) || !is_array($rancherData) )
			throw new NoActiveServiceException($nameMatcher->getName());

		// primitive way of handling docker-compose.yml version 2
		if( array_key_exists('version', $dockerData) && $dockerData['version'] == 2) {
			if( array_key_exists('services', $dockerData ) )
				$dockerData = $dockerData['services'];
			else
				$dockerData = [];
		}
		// primitive way of handling docker-compose.yml version 2
		if( array_key_exists('version', $rancherData) && $rancherData['version'] == 2) {
			if( array_key_exists('services', $rancherData ) )
				$rancherData = $rancherData['services'];
			else
				$rancherData = [];
		}

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
		$matchingServiceNames = [];
		foreach($rancherData as $serviceName => $data) {

			$translatedServiceName = trim(strtolower($serviceName));

			if( in_array($translatedServiceName, $sidekicks) )
				continue;

			if( ! $nameMatcher->match($serviceName) )
				continue;

			if(!array_key_exists('scale', $data))
				continue;

			$containerIsActive = $data['scale'] > 0;
			if( !$containerIsActive )
				continue;

			if( !array_key_exists($serviceName, $dockerData) )
				throw new ServiceMissingInDockerfileException($serviceName);

			$dockerDefinition = $dockerData[$serviceName];
			$dockerDefinition['name'] = $serviceName;
			$matchingServices[] = $dockerDefinition;
			$matchingServiceNames[] = $serviceName;
		}

		if( 1 < count($matchingServices) )
			throw new MultipleActiveServicesException($nameMatcher->getName(), $matchingServiceNames);

		if( empty($matchingServices))
			throw new NoActiveServiceException($nameMatcher->getName());

		return reset($matchingServices);

	}

	/**
	 * @param string $stackName
	 * @param NameMatcher $nameMatcher
	 * @return string
	 */
	public function getActiveService(string $stackName, NameMatcher $nameMatcher) : string {
		$definition = $this->getActiveServiceDefinition($stackName, $nameMatcher);

		return $definition['name'];
	}

	/**
	 * @param string $stackName
	 * @param NameMatcher $nameMatcher
	 * @return string
	 */
	public function getCurrentVersion(string $stackName, NameMatcher $nameMatcher) {
		$currentService = $this->getActiveServiceDefinition($stackName, $nameMatcher);

		if( !array_key_exists('labels', $currentService) || !array_key_exists('version', $currentService['labels']) ) {
			$currentServiceName = $currentService['name'];

			// TODO: using nameMatcher->getName is ugly here. Think of a better way to do this
			return substr($currentServiceName, strlen($nameMatcher->getName().'-') );
		}

		return $currentService['labels']['version'];
	}

	/**
	 * @param string $directory
	 * @param string $stackName
	 */
	public function stop(string $directory, string $stackName) {
		$url = $this->getUrl();
		$process = ProcessBuilder::create([
			$this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName, 'stop'
		])
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new StopFailedException("Command ".$ran->getCommandLine());
	}

	/**
	 * Waits until the given service reaches a state thats matched by the $stateMatcher. The $delayer is called between
	 * runs to wait before trying again.
	 * The matched service data
	 *
	 * @param string $stackName
	 * @param string $serviceName
	 * @param StateMatcher $stateMatcher defaults to SingleStateMatcher('active') - wait until active
	 * @param Delayer $delayer defaults to FixedSleepDelayer(500000) - wait for half a second
	 * @return string
	 */
	public function wait(string $stackName, string $serviceName, StateMatcher $stateMatcher = null, Delayer $delayer = null) {
		if($stateMatcher === null)
			$stateMatcher = new SingleStateMatcher('active');
		if($delayer === null)
			$delayer = new FixedSleepDelayer(500000);

		$stackId = $this->getStackIdByName($stackName);
		$url = implode('/', [
			$this->account->getUrl(),
			'environments',
			$stackId,
			'services'
		]);

		$run = 1;
		do {
			if( 1 < $run )
				$delayer->delay($run);

			$headers = [];
			$this->addAuthHeader($headers);

			$jsonData = $this->apiService->get($url, $headers);
			$data = json_decode($jsonData, true);

			if( !array_key_exists('data', $data) )
				throw new MissingDataException('data', array_keys($data) );

			$objects = $data['data'];
			$service = $this->byNameService->findName($objects, $serviceName);

			++$run;
		} while( !$stateMatcher->match($service) );

		return $service;
	}

	/**
	 * @param string $directory
	 * @param string $stackName
	 * @param array $services
	 */
	public function rm(string $directory, string $stackName, array $services = null) {
		$url = $this->getUrl();
		$command = [ $this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName, 'rm' ];

		if($services === null)
			$command[] = '-f';
		else
			$command = array_merge($command, $services);

		$process = ProcessBuilder::create($command)
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new RemoveFailedException("Command ".$ran->getCommandLine());
	}

	/**
	 * @param $directory
	 * @param $stackName
	 * @param $serviceNames
	 */
	public function create( $directory, $stackName, $serviceNames ) {
		if($serviceNames === null)
			$serviceNames = [];
		if( !is_array($serviceNames) )
			$serviceNames = [$serviceNames];

		$url = $this->getUrl();
		$command = [ $this->account->getRancherCompose(), "-f", "$directory/docker-compose.yml", '-r', "$directory/rancher-compose.yml", '-p', $stackName, 'create' ];

		$command = array_merge($command, $serviceNames);

		$process = ProcessBuilder::create( $command )
			->setTimeout(null)
			->addEnvironmentVariables([
				'RANCHER_URL' => $url,
				'RANCHER_ACCESS_KEY' => $this->account->getKey(),
				'RANCHER_SECRET_KEY' => $this->account->getSecret(),
			])->getProcess();

		$ran = $this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if( $ran->getExitCode() != 0 )
			throw new CreateFailedException("Command ".$ran->getCommandLine());
	}

	/**
	 * @return bool
	 */
	public function isCliMode(): bool {
		return $this->cliMode;
	}

	/**
	 * @param bool $cliMode
	 */
	public function setCliMode( bool $cliMode ) {
		$this->cliMode = $cliMode;
	}

    /**
     * @param $stackName
     * @throws StackNotFoundException
     */
    public function assertStackExists($stackName) {
        $this->getStackIdByName($stackName);
    }
}