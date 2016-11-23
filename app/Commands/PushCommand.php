<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\LoadsBlueprintTrait;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Commands\Traits\DockerTrait;
use Rancherize\Commands\Traits\RancherTrait;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use Rancherize\RancherAccess\RancherAccessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class PushCommand extends Command   {

	use BuildsTrait;
	use RancherTrait;
	use LoadsConfigurationTrait;
	use LoadsBlueprintTrait;
	use DockerTrait;

	protected function configure() {
		$this->setName('push')
			->setDescription('Start an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->loadConfiguration();

		$projectConfiguration = new PrefixConfigurableDecorator($configuration, 'project.');
		$environmentConfiguration = new PrefixConfigurableDecorator($configuration, "project.$environment");
		$config = new ConfigurationFallback($environmentConfiguration, $projectConfiguration);

		$rancherConfiguration = new RancherAccessService($configuration);
		$account = $rancherConfiguration->getAccount( $config->get('account') );

		$rancher = $this->getRancher();
		$rancher->setAccount($account);

		$stackName = $config->get('stack');
		try {
			list($composerConfig, $rancherConfig) = $rancher->retrieveConfig($stackName);

			$this->getBuildService()->createDockerCompose($composerConfig);
			$this->getBuildService()->createRancherCompose($rancherConfig);
		} catch(StackNotFoundException $e) {
			$output->writeln("Stack not found, creating", OutputInterface::VERBOSITY_NORMAL);
			$rancher->createStack($stackName);
		}

		$name = $config->get('NAME');
		$repository = $config->get('repository');
		$repositoryPrefix = $config->get('repository-prefix', '');

		$image = $repository.':'.$repositoryPrefix;

		$this->getBuildService()
			->setImage($image)
			->build($environment, $input, true);

		$this->getDocker()->build($image, './.rancherize/Dockerfile');
		$this->getDocker()->push($image);

		//passthru('docker-compose -f ./.rancherize/docker-compose.yml up -d');

		return 0;
	}


}