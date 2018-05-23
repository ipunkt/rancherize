<?php namespace Rancherize\Commands;

use LogicException;
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
 * Class RestartCommand
 * @package Rancherize\Commands
 */
class RestartCommand extends Command implements LoadsConfiguration {

	use LoadsConfigurationTrait;

	/**
	 * @var DockerService
	 */
	private $dockerService;
	/**
	 * @var BuildService
	 */
	private $buildService;
	/**
	 * @var BlueprintService
	 */
	private $blueprintService;
	/**
	 * @var EnvironmentConfigurationService
	 */
	private $environmentConfigurationService;

	/**
	 * RestartCommand constructor.
	 * @param DockerService $dockerService
	 * @param BuildService $buildService
	 * @param BlueprintService $blueprintService
	 * @param EnvironmentConfigurationService $environmentConfigurationService
	 */
	public function __construct( DockerService $dockerService, BuildService $buildService, BlueprintService $blueprintService,
			EnvironmentConfigurationService $environmentConfigurationService ) {
		parent::__construct();
		$this->dockerService = $dockerService;
		$this->buildService = $buildService;
		$this->blueprintService = $blueprintService;
		$this->environmentConfigurationService = $environmentConfigurationService;
	}

	protected function configure() {
		$this->setName('restart')
			->setDescription('Stop, then start an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	/**
	 * Executes the current command.
	 *
	 * This method is not abstract because you can use this class
	 * as a concrete class. In this case, instead of defining the
	 * execute() method, you set the code to execute by passing
	 * a Closure to the setCode() method.
	 *
	 * @param InputInterface  $input  An InputInterface instance
	 * @param OutputInterface $output An OutputInterface instance
	 *
	 * @return null|int null or 0 if everything went fine, or an error code
	 *
	 * @throws LogicException When this abstract method is not implemented
	 *
	 * @see setCode()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		$container = container();
		$container['shared-network-mode'] = 'service:';

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfigurationService->environmentConfig($configuration, $environment);

		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
		$this->buildService->build($blueprint, $configuration, $environment);

		$this->dockerService
			->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->dockerService->stop('./.rancherize', $config->get('service-name') );

		$this->dockerService->start('./.rancherize', $config->get('service-name') );

		return 0;
	}
}