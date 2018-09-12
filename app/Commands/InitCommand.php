<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Blueprint;
use Rancherize\Commands\Events\InitCommandEvent;
use Rancherize\Commands\Traits\EventTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\ConfigWrapper;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccessService;
use Rancherize\RancherAccess\RancherAccessParsesConfiguration;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\Services\BlueprintService;
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
class InitCommand extends Command implements LoadsConfiguration {

	use IoTrait;
	use LoadsConfigurationTrait;
	use EventTrait;

	/**
	 * @var RancherAccessService
	 */
	private $rancherAccessService;
	/**
	 * @var BlueprintService
	 */
	private $blueprintService;
	/**
	 * @var DockerAccessService
	 */
	private $dockerAccessService;

	/**
	 * InitCommand constructor.
	 * @param RancherAccessService $rancherAccessService
	 * @param BlueprintService $blueprintService
	 * @param DockerAccessService $dockerAccessService
	 */
	public function __construct( RancherAccessService $rancherAccessService, BlueprintService $blueprintService, DockerAccessService $dockerAccessService) {
		parent::__construct();
		$this->rancherAccessService = $rancherAccessService;
		$this->blueprintService = $blueprintService;
		$this->dockerAccessService = $dockerAccessService;
	}

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
			$output->writeln($this->getHelper('formatter')->formatSection('Error', 'At least one environment must be given for init to run'));

		$this->setIo($input, $output);

		$configuration = $this->getConfiguration();

		$blueprint = $this->blueprintService->load($blueprintName, $input->getOptions());

		$configuration->set('project.blueprint', $blueprintName);
		if($this->rancherAccessService instanceof RancherAccessParsesConfiguration)
			$this->rancherAccessService->parse($configuration);

		$accounts = $this->rancherAccessService->availableAccounts();
		if (!$configuration->has('project.default.rancher.account'))
			$configuration->set('project.default.rancher.account', reset($accounts));


		$dockerAccessService = $this->dockerAccessService;
		$dockerAccessService->parse($configuration);
		$dockerAccounts = $dockerAccessService->availableAccounts();
		if (!$configuration->has('project.default.docker.account'))
			$configuration->set('project.default.docker.account', reset($dockerAccounts));

		$initEvent = new InitCommandEvent($configuration);
		$this->getEventDispatcher()->dispatch($initEvent::NAME, $initEvent);

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