<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Commands\Traits\IoCommandTrait;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\JsonLoader;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Rancherize\Commands
 */
class InitCommand extends Command {

	use IoCommandTrait;

	protected function configure() {
		$this->setName('init')
			->setDescription('Initialize all given arguments')
			->addArgument('blueprint', InputArgument::REQUIRED)
			->addArgument('environments', InputArgument::IS_ARRAY)
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


		$blueprintFactory = $this->getBlueprintFactory();
		$blueprint = $blueprintFactory->get($blueprintName);

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

		$prefixedConfiguration = new PrefixConfigurableDecorator($configuration, "project.$environmentName");

		$blueprint->init($prefixedConfiguration, $this->getInput(), $this->getOutput());

	}

	private function saveConfiguration($configuration) {

		/**
		 * @var ConfigWrapper $configWrapper
		 */

		$configWrapper = container('config-wrapper');
		$configWrapper->saveProjectConfig($configuration);
	}


}