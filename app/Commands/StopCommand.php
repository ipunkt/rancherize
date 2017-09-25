<?php namespace Rancherize\Commands;

use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\EnvironmentConfigurationService;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Services\BlueprintService;
use Rancherize\Services\BuildService;
use Rancherize\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Stop the given environment
 * This triggers the blueprint to build the infrastructure and uses docker to stop it
 */
class StopCommand extends Command implements LoadsConfiguration {

	use LoadsConfigurationTrait;
	/**
	 * @var BuildService
	 */
	private $buildService;
	/**
	 * @var BlueprintService
	 */
	private $blueprintService;
	/**
	 * @var DockerService
	 */
	private $dockerService;
	/**
	 * @var EnvironmentConfigurationService
	 */
	private $environmentConfigurationService;

	/**
	 * StopCommand constructor.
	 * @param DockerService $dockerService
	 * @param BuildService $buildService
	 * @param BlueprintService $blueprintService
	 * @param EnvironmentConfigurationService $environmentConfigurationService
	 */
	public function __construct( DockerService $dockerService, BuildService $buildService, BlueprintService $blueprintService,
			EnvironmentConfigurationService $environmentConfigurationService) {
		parent::__construct();
		$this->buildService = $buildService;
		$this->blueprintService = $blueprintService;
		$this->dockerService = $dockerService;
		$this->environmentConfigurationService = $environmentConfigurationService;
	}

	protected function configure() {
		$this->setName('stop')
			->setDescription('Stop an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfigurationService->environmentConfig($configuration, $environment);

		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
		$this->buildService->build($blueprint, $configuration, $environment);

		$this->dockerService
			->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->dockerService->stop('./.rancherize', $config->get('service-name'));

		return 0;
	}


}