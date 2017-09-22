<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\LoadsConfiguration;
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
 * This command builds deployment files as if they were used in the start or push command.
 * Can be used to inspect the files for correctness before starting or pushing
 */
class BuildCommand extends Command implements LoadsConfiguration {

	use LoadsConfigurationTrait;
	use BlueprintTrait;
	use ValidateTrait;
	/**
	 * @var BuildService
	 */
	private $buildService;

	/**
	 * BuildCommand constructor.
	 * @param BuildService $buildService
	 */
	public function __construct( BuildService $buildService) {
		parent::__construct();
		$this->buildService = $buildService;
	}

	protected function configure() {
		$this->setName('build')
			->setDescription('Build deployment files for the given environment')
			->addArgument('environment', InputArgument::REQUIRED)
			->addArgument('version', InputArgument::OPTIONAL)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');
		$version = $input->getArgument('version');

		$buildService = $this->buildService;

		$configuration = $this->getConfiguration();
		$blueprint = $this->blueprintService->byConfiguration($configuration, $input->getOptions());

		if($version !== null)
			$buildService->setVersion($version);

		$buildService->build($blueprint, $configuration, $environment);

		return 0;
	}


}