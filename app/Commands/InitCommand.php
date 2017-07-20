<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Services\ConfigWrapper;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccessService;
use Rancherize\RancherAccess\RancherAccessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Rancherize\Commands
 *
 * Create the given environments in the configuration with explanatory default options
 */
class InitCommand extends Command {

	use IoTrait;
	use LoadsConfigurationTrait;
	use BlueprintTrait;

	protected function configure() {
		$this->setName('init')
			->setDescription('Initialize all given arguments')
			->addArgument('blueprint', InputArgument::REQUIRED)
			->addArgument('environments', InputArgument::IS_ARRAY)
			->addOption('dev', null, InputOption::VALUE_NONE, false);
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$blueprintName = $input->getArgument('blueprint');
		$environments = $input->getArgument('environments');

		if (empty($environments))
			$output->writeln($this->getHelper('formatter')->formatSection('Error', 'At least one environment mus be given for init to run'));

		$this->setIo($input, $output);

		$configuration = $this->loadConfiguration();


		$blueprint = $this->getBlueprintService()->load($blueprintName, $input->getOptions());

		$configuration->set('project.blueprint', $blueprintName);
		$rancherAccessService = new RancherAccessService($configuration);

		$accounts = $rancherAccessService->availableAccounts();
		if (!$configuration->has('project.default.rancher.account'))
			$configuration->set('project.default.rancher.account', reset($accounts));


		/**
		 * @var DockerAccessService $dockerAccessService
		 */
		$dockerAccessService = container('docker-access-service');
		$dockerAccessService->parse($configuration);
		$dockerAccounts = $dockerAccessService->availableAccounts();
		if (!$configuration->has('project.default.docker.account'))
			$configuration->set('project.default.docker.account', reset($dockerAccounts));

		foreach ($environments as $environment) {

			$this->initEnvironment($blueprint, $configuration, $environment);

		}

		$this->saveConfiguration($configuration);

		return 0;
	}


	/**
	 * @param Blueprint $blueprint
	 * @param Configurable $configuration
	 * @param string $environmentName
	 */
	private function initEnvironment(Blueprint $blueprint, Configurable $configuration, string $environmentName) {

		if (!$this->getOutput()->isQuiet())
			$this->getOutput()->writeln([
				"",
				"Initializing environment $environmentName",
				"=========================================",
			]);


		$blueprint->init($configuration, $environmentName, $this->getInput(), $this->getOutput());

	}

	private function saveConfiguration($configuration) {

		/**
		 * @var ConfigWrapper $configWrapper
		 */
		$configWrapper = container('config-wrapper');
		$configWrapper->saveProjectConfig($configuration);
	}


}