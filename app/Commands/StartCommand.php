<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\DockerTrait;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Services\BuildService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Start the given infrastructure on the local machine
 * Triggers the blueprint to build the environment and then starts it in docker
 */
class StartCommand extends Command implements LoadsConfiguration {

	use DockerTrait;
	use LoadsConfigurationTrait;
	use EnvironmentConfigurationTrait;
	use BlueprintTrait;
	/**
	 * @var BuildService
	 */
	private $buildService;

	/**
	 * StartCommand constructor.
	 * @param BuildService $buildService
	 */
	public function __construct( BuildService $buildService) {
		parent::__construct();
		$this->buildService = $buildService;
	}

	protected function configure() {
		$this->setName('start')
			->setDescription('Start an environment on the local machine')
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

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfig($configuration, $environment);

		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
		$infrastructure = $this->buildService->build($blueprint, $configuration, $environment);

		$this->getDocker()
			->setOutput($output)
			->setProcessHelper($this->getHelper('process'));

		$this->getDocker()->start('./.rancherize', $config->get('service-name') );

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
