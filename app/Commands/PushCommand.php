<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Commands\Traits\DockerTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Commands\Traits\RancherTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccessService;
use Rancherize\RancherAccess\Exceptions\NoActiveServiceException;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class PushCommand extends Command   {

	use IoTrait;
	use BuildsTrait;
	use RancherTrait;
	use LoadsConfigurationTrait;
	use DockerTrait;
	use EnvironmentConfigurationTrait;
	use BlueprintTrait;

	protected function configure() {
		$this->setName('push')
			->setDescription('Start an environment on the local machine')
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

		$rancherConfiguration = new RancherAccessService($configuration);
		$account = $rancherConfiguration->getAccount( $config->get('account') );

		$rancher = $this->getRancher();
		$rancher->setAccount($account)
			->setOutput($output)
			->setProcessHelper( $this->getHelper('process'));

		$stackName = $config->get('stack');
		try {
			list($composerConfig, $rancherConfig) = $rancher->retrieveConfig($stackName);

			$this->getBuildService()->createDockerCompose($composerConfig);
			$this->getBuildService()->createRancherCompose($rancherConfig);
		} catch(StackNotFoundException $e) {
			$output->writeln("Stack not found, creating", OutputInterface::VERBOSITY_NORMAL);
			$rancher->createStack($stackName);
		}

		$repository = $config->get('repository');
		$repositoryPrefix = $config->get('repository-prefix', '');

		$image = $repository.':'.$repositoryPrefix.$version;

		$blueprint = $this->getBlueprintService()->byConfiguration($configuration, $input->getArguments());
		$this->getBuildService()
			->setVersion($version)
			->build($blueprint, $configuration, $environment, true);

		$dockerService = $this->getDocker();
		$dockerService->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->buildImage($dockerService, $configuration, $config, $image);

		$name = $config->get('NAME');
		$versionizedName = $name.'-'.$version;
		try {
			$activeStack = $this->getRancher()->getActiveService($stackName, $name);

			$this->getRancher()->upgrade('./.rancherize', $stackName, $activeStack, $versionizedName);
		} catch(NoActiveServiceException $e) {
			$this->getRancher()->start('./.rancherize', $stackName);
		}


		return 0;
	}

	/**
	 * @param InputInterface $input
	 * @param DockerService $dockerService
	 * @param Configuration $configuration
	 * @param Configuration $config
	 * @param $image
	 * @internal param $dockerAccount
	 */
	protected function buildImage(DockerService $dockerService, Configuration $configuration, Configuration $config, $image) {

		if ( $this->getInput()->getOption('image-exists') ) {
			$this->getOutput()->writeln("Option image-exists was set, skipping build.", OutputInterface::VERBOSITY_VERBOSE);

			return;
		}

		$dockerService->build($image, './.rancherize/Dockerfile');

		$dockerConfiguration = new DockerAccessService($configuration);
		$dockerAccount = $dockerConfiguration->getAccount( $config->get('docker-account') );


		$dockerService->login($dockerAccount->getUsername(), $dockerAccount->getPassword());
		$dockerService->push($image);
	}


}