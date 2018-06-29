<?php namespace Rancherize\Commands;

use Rancherize\Commands\Types\LocalCommand;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\EnvironmentConfigurationService;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Services\BlueprintService;
use Rancherize\Services\BuildService;
use Rancherize\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Start the given infrastructure on the local machine
 * Triggers the blueprint to build the environment and then starts it in docker
 */
class StartCommand extends Command implements LoadsConfiguration, LocalCommand {

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
	 * StartCommand constructor.
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
		$this->setName('start')
			->setDescription('Start an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
			->addOption('rebuild', 'r', InputOption::VALUE_NONE, 'force rebuild of images. Use e.g. to update debug images.')
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
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfigurationService->environmentConfig($configuration, $environment);

		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
		$infrastructure = $this->buildService->build($blueprint, $configuration, $environment);

		$this->dockerService
			->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		if( $input->getOption('rebuild') )
			$this->dockerService->buildImages('./.rancherize', $config->get('service-name') );

		$this->dockerService->start('./.rancherize', $config->get('service-name') );

		foreach( $infrastructure->getServices() as $service ) {
			$exposedPorts = $service->getExposedPorts();
			if( empty($exposedPorts) )
				continue;

			$serviceName = $service->getName();
			$ports = implode(', ', $exposedPorts);

			$firstLink = (count($exposedPorts) > 0)
				? ' (http://localhost:' . current($exposedPorts) . ')'
				: '';

			$output->writeln("Service $serviceName was exposed to the ports ${ports}${firstLink}");
		}

		return 0;
	}
}
