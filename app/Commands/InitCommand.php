<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\JsonLoader;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Rancherize\Commands
 */
class InitCommand extends Command {

	use IoTrait;

	protected function configure() {
		$this->setName('init')
			->setDescription('Initialize all given arguments')
			->addArgument('blueprint', InputArgument::REQUIRED)
			->addArgument('environments', InputArgument::IS_ARRAY)
			->addOption('dev', null, InputOption::VALUE_NONE, false)
		;
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$blueprintName = $input->getArgument('blueprint');
		$environments = $input->getArgument('environments');

		if( empty($environments) )
			$output->writeln( $this->getHelper('formatter')->formatSection('Error', 'At least one environment mus be given for init to run') );

		$this->setIo($input, $output);

		$configuration = $this->loadConfiguration();


		$blueprint = $this->loadBlueprint($input, $blueprintName);

		$configuration->set('project.blueprint', $blueprintName);

		foreach($environments as $environment) {

			$this->initEnvironment($blueprint, $configuration, $environment);

		}

		$this->saveConfiguration($configuration);

		return 0;
	}

	/**
	 * @return BlueprintFactory
	 */
	private function getBlueprintFactory() {
		return container('blueprint-factory');
	}

	/**
	 * @return Configurable
	 */
	private function loadConfiguration() {
		/**
		 * @var ConfigWrapper $configWrapper
		 */
		$configWrapper = container('config-wrapper');
		$config = $configWrapper->configuration();

		$configWrapper->loadGlobalConfig($config);
		$configWrapper->loadProjectConfig($config);


		return $config;
	}


	/**
	 * @param Blueprint $blueprint
	 * @param Configurable $configuration
	 * @param string $environmentName
	 */
	private function initEnvironment(Blueprint $blueprint, Configurable $configuration, string $environmentName) {

		if( !$this->getOutput()->isQuiet() )
			$this->getOutput()->writeln( [
				"",
				"Initializing environment $environmentName",
				"=========================================",
			]);

		$prefixedConfiguration = new PrefixConfigurableDecorator($configuration, "project.$environmentName.");

		$blueprint->init($prefixedConfiguration, $this->getInput(), $this->getOutput());

	}

	private function saveConfiguration($configuration) {

		/**
		 * @var ConfigWrapper $configWrapper
		 */

		$configWrapper = container('config-wrapper');
		$configWrapper->saveProjectConfig($configuration);
	}

	/**
	 * @param InputInterface $input
	 * @param $blueprintName
	 * @return Blueprint
	 */
	protected function loadBlueprint(InputInterface $input, $blueprintName):Blueprint {
		$blueprintFactory = $this->getBlueprintFactory();
		$blueprint = $blueprintFactory->get($blueprintName);

		foreach ($input->getOptions() as $name => $value)
			$blueprint->setFlag($name, $value);
		return $blueprint;
	}


}