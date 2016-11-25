<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Commands\Traits\EnvironmentTrait;
use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Ensure that the given environments have the minimum configuration options present to be started / pushed
 */
class ValidateCommand extends Command   {

	use LoadsConfigurationTrait;
	use BlueprintTrait;
	use ValidateTrait;
	use EnvironmentTrait;

	protected function configure() {
		$this->setName('validate')
			->setDescription('Validate the given environment configuration, or all environments if none was given')
			->addArgument('environments', InputArgument::IS_ARRAY)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environments = $input->getArgument('environments');

		$configuration = $this->loadConfiguration();

		if( empty($environments) ) {

			$environments = $this->getEnvironmentService()->allAvailable($configuration);

		}

		$validateService = $this->getValidateService();
		$blueprint = $this->getBlueprintService()->byConfiguration($configuration, []);

		foreach($environments as $environment) {
			$headline = "Validating $environment";
			$output->writeln([
				'',
				$headline,
				str_repeat('=', strlen($headline))
			]);

			try {
				$validateService->validate($blueprint, $configuration, $environment);
			} catch(ValidationFailedException $e) {
				$validateService->print($e, $output);
			}
		}

		return 0;
	}


}