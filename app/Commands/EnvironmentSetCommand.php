<?php namespace Rancherize\Commands;
use Rancherize\Commands\Traits\EnvironmentTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\ConfigWrapper;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class EnvironmentAddCommand
 * @package Rancherize\Commands
 *
 * Set the value for the environment variable with the given name in all known environments
 */
class EnvironmentSetCommand extends Command implements LoadsConfiguration {

	use IoTrait;
	use LoadsConfigurationTrait;
	use EnvironmentTrait;
	use EnvironmentConfigurationTrait;

	protected function configure() {
		$this->setName('environment:set')
			->setDescription('Add a given environment value to all app environments')
			->addArgument('name', InputArgument::REQUIRED)
		;
		parent::configure();
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		$this->setIo($input, $output);

		$configuration = $this->getConfiguration();

		$this->setVariable($input, $output, $configuration);

		/**
		 * @var ConfigWrapper $configWrapper
		 */
		$configWrapper = container('config-wrapper');
		$configWrapper->saveProjectConfig($configuration);
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @param $configuration
	 */
	protected function setVariable(InputInterface $input, OutputInterface $output, Configurable $configuration) {

		$name = $input->getArgument('name');

		$environments = $this->getEnvironmentService()->allAvailable($configuration);
		foreach($environments as $environment) {

			$environmentConfig = $this->environmentConfig($configuration, $environment);

			$output->writeln( $output->getFormatter()->format("<info>$environment</info>") );

			$currentValue = $environmentConfig->get("environment.$name");
			$question = new Question("Please enter the value for the Environment Variable $environment.$name ($currentValue): ", $currentValue);
			$value = $this->getHelper('question')->ask($input, $output, $question);

			$output->writeln( $output->getFormatter()->format("Setting <info>project.environments.$environment.$name</info> to <info>$value</info>"), OutputInterface::VERBOSITY_VERBOSE );
			$configuration->set("project.environments.$environment.environment.$name", $value);
		}

	}
}