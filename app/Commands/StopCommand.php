<?php namespace Rancherize\Commands;

use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
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
	use EnvironmentConfigurationTrait;
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
	 * StopCommand constructor.
	 * @param DockerService $dockerService
	 * @param BuildService $buildService
	 * @param BlueprintService $blueprintService
	 */
	public function __construct( DockerService $dockerService, BuildService $buildService, BlueprintService $blueprintService) {
		parent::__construct();
		$this->buildService = $buildService;
		$this->blueprintService = $blueprintService;
		$this->dockerService = $dockerService;
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
		$config = $this->environmentConfig($configuration, $environment);

		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
		$this->buildService->build($blueprint, $configuration, $environment);

		$this->dockerService
			->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->dockerService->stop('./.rancherize', $config->get('service-name'));

		return 0;
	}


}