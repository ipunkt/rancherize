<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class BuildCommand extends Command   {

	use LoadsConfigurationTrait;
	use BlueprintTrait;
	use BuildsTrait;
	use ValidateTrait;

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

		$buildService = $this->getBuildService();

		$configuration = $this->loadConfiguration();
		$blueprint = $this->getBlueprintService()->byConfiguration($configuration, $input->getOptions());

		if($version !== null)
			$buildService->setVersion($version);

		$buildService->build($blueprint, $configuration, $environment);

		return 0;
	}


}