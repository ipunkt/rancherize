<?php namespace Rancherize\Commands;

use LogicException;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
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
	use BlueprintTrait;
	use EnvironmentConfigurationTrait;

	/**
	 * @var DockerService
	 */
	private $dockerService;
	/**
	 * @var BuildService
	 */
	private $buildService;

	/**
	 * RestartCommand constructor.
	 * @param DockerService $dockerService
	 * @param BuildService $buildService
	 */
	public function __construct( DockerService $dockerService, BuildService $buildService ) {
		parent::__construct();
		$this->dockerService = $dockerService;
		$this->buildService = $buildService;
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

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfig($configuration, $environment);

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