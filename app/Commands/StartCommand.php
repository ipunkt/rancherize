<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\LoadsBlueprintTrait;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use RancherizeTest\Configuration\PrefixedConfigurationTest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class StartCommand extends Command   {

	use LoadsConfigurationTrait;
	use LoadsBlueprintTrait;

	protected function configure() {
		$this->setName('start')
			->setDescription('Start an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->loadConfiguration();
		$blueprintName = $configuration->get('project.blueprint');
		$blueprint = $this->loadBlueprint($input, $blueprintName);

		$blueprint->validate($configuration, $environment);
		$blueprint->build($configuration, $environment);

		return 0;
	}


}