<?php namespace Rancherize\Commands;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Exceptions\InvalidFormatException;
use Rancherize\Configuration\Services\GlobalConfiguration;
use Rancherize\Exceptions\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class RancherAccessCommand
 * @package Rancherize\Commands
 */
class RancherAccessCommand extends Command {

	protected function configure() {
		$this->setName('rancher:access')
			->setDescription('Initialize Rancher access')
		;
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$this->assertInteractive($input, $output);


		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * @var GlobalConfiguration $globalConfiguration
		 */
		$globalConfiguration = container('global-config-service');

		$this->validateGlobalConfiguration($input, $output, $globalConfiguration, $configuration, $formatter);

		$this->editGlobalConfiguration($input, $output, $globalConfiguration, $configuration, $question);
	}

	/**
	 * @param OutputInterface $output
	 * @param Configurable $configuration
	 * @param GlobalConfiguration $globalConfiguration
	 */
	protected function createDefaultConfiguration(OutputInterface $output, Configurable $configuration, GlobalConfiguration $globalConfiguration):void {
		$formatter = $this->getHelper('formatter');

		$globalConfiguration->makeDefault($configuration);
		$globalConfiguration->save($configuration);

		if (OutputInterface::VERBOSITY_VERY_VERBOSE <= $output->getVerbosity())
			$output->writeln($formatter->formatSection('Default', "Global configuration file was created."));
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @param Configurable $configuration
	 * @param GlobalConfiguration $globalConfiguration
	 */
	private function invalidFormatDetected( InputInterface $input, OutputInterface $output,Configurable $configuration, GlobalConfiguration $globalConfiguration) {
		if($output->isQuiet())
			return;

		$formatter = $this->getHelper('formatter');
		$question = $this->getHelper('question');
		$overwriteWithDefaultQuestion = new ConfirmationQuestion('The global configuration file is not in a valid format. Replace with default? [y/N]', false);

		if( !$question->ask($input, $output, $overwriteWithDefaultQuestion) ) {
			if( OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity() )
				$output->writeln( $formatter->formatSection( 'Default', 'NOT overriding invalid config') );

			return;
		}


		$this->createDefaultConfiguration($output, $configuration, $globalConfiguration);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	private function assertInteractive(InputInterface $input, OutputInterface $output) {
		if( $input->isInteractive() )
			return;

		throw new Exception("Configuring rancher access requires an interactive terminal session");
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @param GlobalConfiguration $globalConfiguration
	 * @param Configurable $configuration
	 */
	protected function validateGlobalConfiguration(InputInterface $input, OutputInterface $output, GlobalConfiguration $globalConfiguration, Configurable $configuration):void {

		$formatter = $this->getHelper('formatter');

		try {
			$globalConfiguration->load($configuration);
		} catch (InvalidFormatException $e) {

			$this->invalidFormatDetected($input, $output, $configuration, $globalConfiguration);

		} catch (FileNotFoundException $e) {

			$this->createDefaultConfiguration($output, $configuration, $globalConfiguration);

			if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity())
				$output->writeln($formatter->formatSection('Default', "No configuration file was found. Creating default"));

			$this->createDefaultConfiguration($output, $configuration, $globalConfiguration);
		}
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @param $globalConfiguration
	 * @param $configuration
	 */
	protected function editGlobalConfiguration(InputInterface $input, OutputInterface $output, GlobalConfiguration $globalConfiguration, Configurable $configuration):void {

		$question = $this->getHelper('question');

		$configPath = $globalConfiguration->getPath();
		$editor = getenv('EDITOR') ?: "vim";
		$returnValue = 0;

		$forceInvalidConfigurationQuestion = new ConfirmationQuestion('The global configuration file is not in a valid json format. Quit anyway? [y/N]', false);
		do {
			$validConfig = true;
			$force = false;


			passthru("$editor '$configPath'", $returnValue);

			try {
				$globalConfiguration->load($configuration);
			} catch (InvalidFormatException $e) {
				$validConfig = false;
				$force = $question->ask($input, $output, $forceInvalidConfigurationQuestion);

			}

		} while (!$validConfig && !$force);
	}
}