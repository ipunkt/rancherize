<?php namespace Rancherize\Commands;
use Rancherize\Commands\Traits\IoCommandTrait;
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

	use IoCommandTrait;

	protected function configure() {
		$this->setName('rancher:access')
			->setDescription('Initialize Rancher access')
		;
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$this->setIo($input, $output);

		$this->assertInteractive();


		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * @var GlobalConfiguration $globalConfiguration
		 */
		$globalConfiguration = container('global-config-service');

		$this->validateGlobalConfiguration( $globalConfiguration, $configuration);

		$this->editGlobalConfiguration( $globalConfiguration, $configuration);
	}

	/**
	 * @param Configurable $configuration
	 * @param GlobalConfiguration $globalConfiguration
	 */
	protected function createDefaultConfiguration( Configurable $configuration, GlobalConfiguration $globalConfiguration) {
		$formatter = $this->getHelper('formatter');

		$globalConfiguration->makeDefault($configuration);
		$globalConfiguration->save($configuration);

		if (OutputInterface::VERBOSITY_VERY_VERBOSE <= $this->output->getVerbosity())
			$this->output->writeln($formatter->formatSection('Default', "Global configuration file was created."));
	}

	/**
	 * @param Configurable $configuration
	 * @param GlobalConfiguration $globalConfiguration
	 */
	private function invalidFormatDetected( Configurable $configuration, GlobalConfiguration $globalConfiguration) {
		if($this->output->isQuiet())
			return;

		$formatter = $this->getHelper('formatter');
		$question = $this->getHelper('question');
		$overwriteWithDefaultQuestion = new ConfirmationQuestion('The global configuration file is not in a valid format. Replace with default? [y/N]', false);

		if( !$question->ask($this->input, $this->output, $overwriteWithDefaultQuestion) ) {
			if( OutputInterface::VERBOSITY_VERBOSE <= $this->output->getVerbosity() )
				$this->output->writeln( $formatter->formatSection( 'Default', 'NOT overriding invalid config') );

			return;
		}


		$this->createDefaultConfiguration( $configuration, $globalConfiguration);
	}

	/**
	 *
	 */
	private function assertInteractive() {
		if( $this->input->isInteractive() )
			return;

		throw new Exception("Configuring rancher access requires an interactive terminal session");
	}

	/**
	 * @param GlobalConfiguration $globalConfiguration
	 * @param Configurable $configuration
	 */
	protected function validateGlobalConfiguration(GlobalConfiguration $globalConfiguration, Configurable $configuration) {

		$formatter = $this->getHelper('formatter');

		try {
			$globalConfiguration->load($configuration);
		} catch (InvalidFormatException $e) {

			$this->invalidFormatDetected($configuration, $globalConfiguration);

		} catch (FileNotFoundException $e) {

			$this->createDefaultConfiguration($configuration, $globalConfiguration);

			if (OutputInterface::VERBOSITY_VERBOSE <= $this->output->getVerbosity())
				$this->output->writeln($formatter->formatSection('Default', "No configuration file was found. Creating default"));

			$this->createDefaultConfiguration($configuration, $globalConfiguration);
		}
	}

	/**
	 * @param $globalConfiguration
	 * @param $configuration
	 */
	protected function editGlobalConfiguration(GlobalConfiguration $globalConfiguration, Configurable $configuration) {

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
				$force = $question->ask($this->input, $this->output, $forceInvalidConfigurationQuestion);

			}

		} while (!$validConfig && !$force);
	}
}