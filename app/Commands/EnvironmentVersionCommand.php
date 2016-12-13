<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\RancherTrait;
use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\RancherAccess\RancherAccessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * This command builds deployment files as if they were used in the start or push command.
 * Can be used to inspect the files for correctness before starting or pushing
 */
class EnvironmentVersionCommand extends Command   {

	use LoadsConfigurationTrait;
	use BlueprintTrait;
	use ValidateTrait;
	use RancherTrait;
	use EnvironmentConfigurationTrait;

	protected function configure() {
		$this->setName('environment:version')
			->setDescription('Print the currently pushed version of the environment')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->loadConfiguration();
		$config = $this->environmentConfig($configuration, $environment);

		$rancherConfiguration = new RancherAccessService($configuration);
		$account = $rancherConfiguration->getAccount( $config->get('rancher.account') );

		$rancher = $this->getRancher();
		$rancher->setAccount($account)
			->setOutput($output);

		$stackName = $config->get('rancher.stack');
		$name = $config->get('service-name');

		$version = $rancher->getCurrentVersion($stackName, $name);

		$output->writeln($version);

		return 0;
	}


}