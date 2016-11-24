<?php namespace Rancherize\Commands;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class ValidateCommand extends Command   {

	use LoadsConfigurationTrait;

	protected function configure() {
		$this->setName('validate')
			->setDescription('Validate the given environment configuration, or all environments if none was given')
			->addArgument('environments', InputArgument::IS_ARRAY)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environments = $input->getArgument('environment');

		$configuration = $this->loadConfiguration();

		if( empty($environments) )
			$environments = array_keys( $configuration->get('project.environment') );

		foreach($environments as $environment)
			$this->validateEnvironment($environment, $configuration);

		return 0;
	}

	/**
	 * @param string $environment
	 * @param Configuration $configuration
	 */
	private function validateEnvironment($environment, $configuration) {
	}


}