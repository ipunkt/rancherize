<?php namespace Rancherize\Commands;
use Rancherize\Commands\Traits\BuildsTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class BuildCommand extends Command   {

	use BuildsTrait;

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

		if($version !== null)
			$buildService->setVersion($version);

		$buildService->build($environment, $input);

		return 0;
	}


}