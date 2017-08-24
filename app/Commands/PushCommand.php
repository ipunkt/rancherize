<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Commands\Traits\DockerTrait;
use Rancherize\Commands\Traits\EventTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Commands\Traits\RancherTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccessConfigService;
use Rancherize\Docker\DockerAccount;
use Rancherize\RancherAccess\Exceptions\NoActiveServiceException;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use Rancherize\RancherAccess\HealthStateMatcher;
use Rancherize\RancherAccess\InServiceCheckerTrait;
use Rancherize\RancherAccess\RancherAccessConfigService;
use Rancherize\RancherAccess\SingleStateMatcher;
use Rancherize\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Push the given environment to rancher. This will trigger the blueprint to build the infrastructure and deploy or
 * upgrade it in the given stack in rancher
 */
class PushCommand extends Command   {

	use IoTrait;
	use BuildsTrait;
	use RancherTrait;
	use LoadsConfigurationTrait;
	use DockerTrait;
	use EnvironmentConfigurationTrait;
	use BlueprintTrait;
	use InServiceCheckerTrait;
	use EventTrait;

	protected function configure() {
		$this->setName('push')
			->setDescription('Start or upgrade the given environment in Rancher')
			->addArgument('environment', InputArgument::REQUIRED)
			->addArgument('version', InputArgument::REQUIRED)
			->addOption('image-exists', 'i', InputOption::VALUE_NONE, 'Do not build and push the image to dockerhub')
		;
	}


	protected function execute(InputInterface $input, OutputInterface $output) {

		$this->setIo($input,$output);

		$environment = $input->getArgument('environment');
		$version = $input->getArgument('version');

		$configuration = $this->loadConfiguration();
		$config = $this->environmentConfig($configuration, $environment);

		$rancherConfiguration = new RancherAccessConfigService($configuration);
		$account = $rancherConfiguration->getAccount( $config->get('rancher.account') );

		$rancher = $this->getRancher();
		$rancher->setAccount($account)
			->setOutput($output)
			->setProcessHelper( $this->getHelper('process'));

		$stackName = $config->get('rancher.stack');
		try {
			list($composerConfig, $rancherConfig) = $rancher->retrieveConfig($stackName);

			$this->getBuildService()->createDockerCompose($composerConfig);
			$this->getBuildService()->createRancherCompose($rancherConfig);
		} catch(StackNotFoundException $e) {
			$output->writeln("Stack not found, creating", OutputInterface::VERBOSITY_NORMAL);
			$rancher->createStack($stackName);

			$this->getBuildService()->createDockerCompose('');
			$this->getBuildService()->createRancherCompose('');
		}

		$repository = $config->get('docker.repository');
		$versionPrefix = $config->get('docker.version-prefix', '');

		$image = $repository.':'.$versionPrefix.$version;

		$dockerAccount = $this->login($configuration, $config);

		$blueprint = $this->getBlueprintService()->byConfiguration($configuration, $input->getArguments());
		$this->getBuildService()
			->setVersion($version)
			->setDockerAccount($dockerAccount)
			->build($blueprint, $configuration, $environment, true);

		$dockerService = $this->getDocker();
		$dockerService->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->buildImage($dockerService, $configuration, $config, $image, $dockerAccount);

		$name = $config->get('service-name');

		$versionizedName = $name.'-'.$version;
		if( $this->getInServiceChecker()->isInService($config) )
			$versionizedName = $name;

		try {
			$activeStack = $this->getRancher()->getActiveService($stackName, $name);

			$isInServiceUpgrade = $activeStack === $versionizedName;
			if( $isInServiceUpgrade ) {
				$this->inServiceUpgrade( $stackName, $versionizedName, $config );
				return 0;
			}

			$this->rollingUpgrade( $stackName, $activeStack, $versionizedName );
		} catch(NoActiveServiceException $e) {

			$this->createNewService( $stackName, $versionizedName, $config);
		}

		return 0;
	}

	protected function login(Configuration $configuration, Configuration $config) {

		/**
		 * @var DockerAccessConfigService $dockerAccessService
		 */
		$dockerAccessService = container('docker-access-service');
		$dockerAccessService->parse($configuration);
		$dockerAccount = $dockerAccessService->getAccount( $config->get('docker.account') );

		return $dockerAccount;

	}

	/**
	 * @param InputInterface $input
	 * @param DockerService $dockerService
	 * @param Configuration $configuration
	 * @param Configuration $config
	 * @param $image
	 * @internal param $dockerAccount
	 */
	protected function buildImage(DockerService $dockerService, Configuration $configuration, Configuration $config, $image, DockerAccount $dockerAccount) {

		if ( $this->getInput()->getOption('image-exists') ) {
			$this->getOutput()->writeln("Option image-exists was set, skipping build.", OutputInterface::VERBOSITY_VERBOSE);

			return;
		}


		$server = $dockerAccount->getServer();
		if( !empty($server) ) {
			$serverHost = parse_url($server, PHP_URL_HOST);
			$image = $serverHost.'/'.$image;
		}

		$dockerService->build($image, './.rancherize/Dockerfile');
		$dockerService->login($dockerAccount->getUsername(), $dockerAccount->getPassword(), $dockerAccount->getServer());
		$dockerService->push($image);
	}

	/**
	 * @param $serviceNames
	 * @param $config
	 * @return PushCommandInServiceUpgradeEvent
	 */
	protected function makeInServiceEvent( $serviceNames, $config ): PushCommandInServiceUpgradeEvent {
		$inServiceUpgradeEvent = new PushCommandInServiceUpgradeEvent();
		$inServiceUpgradeEvent->setServiceNames( $serviceNames );
		$inServiceUpgradeEvent->setConfiguration( $config );
		$inServiceUpgradeEvent->setForceUpgrade( false );
		return $inServiceUpgradeEvent;
	}

	/**
	 * @param $serviceNames
	 * @param $config
	 * @return PushCommandStartEvent
	 */
	protected function makeStartEvent( $serviceNames, $config ): PushCommandStartEvent {
		$inServiceUpgradeEvent = new PushCommandStartEvent();
		$inServiceUpgradeEvent->setServiceNames( $serviceNames );
		$inServiceUpgradeEvent->setConfiguration( $config );
		return $inServiceUpgradeEvent;
	}

	/**
	 * @param $versionizedName
	 * @param $config
	 * @param $stackName
	 * @return array
	 */
	protected function inServiceUpgrade( $stackName, $versionizedName, Configuration $config ): array {
		$serviceNames = [$versionizedName];
		$startEvent = $this->makeInServiceEvent( $serviceNames, $config );
		$this->getEventDispatcher()->dispatch( PushCommandInServiceUpgradeEvent::NAME, $startEvent );
		$serviceNames = $startEvent->getServiceNames();
		$forcedUpgrade = $startEvent->isForceUpgrade();

		$this->getRancher()->start( './.rancherize', $stackName, $serviceNames, true, $forcedUpgrade );

		// Use default Matcher
		$stateMatcher = new SingleStateMatcher( 'upgraded' );
		if ( $config->get( 'rancher.upgrade-healthcheck', false ) )
			$stateMatcher = new HealthStateMatcher( 'healthy' );

		$this->getRancher()->wait( $stackName, $versionizedName, $stateMatcher );
		// TODO: set timeout and roll back the upgrade if the timeout is reached without health confirmation.

		$this->getRancher()->confirm( './.rancherize', $stackName, [$versionizedName] );
		return array($serviceNames, $startEvent);
	}

	/**
	 * @param $stackName
	 * @param $activeStack
	 * @param $versionizedName
	 */
	protected function rollingUpgrade( $stackName, $activeStack, $versionizedName ) {
		$this->getRancher()->upgrade( './.rancherize', $stackName, $activeStack, $versionizedName );
	}

	/**
	 * @param string $stackName
	 * @param string $versionizedName
	 * @param Configuration $config
	 */
	protected function createNewService( $stackName, $versionizedName, Configuration $config ) {
		$serviceNames = [$versionizedName];
		$startEvent = $this->makeStartEvent( $serviceNames, $config );
		$this->getEventDispatcher()->dispatch( PushCommandStartEvent::NAME, $startEvent );
		$serviceNames = $startEvent->getServiceNames();

		$this->getRancher()->start( './.rancherize', $stackName, $serviceNames );
	}


}