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
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$this->getBuildService()->build($environment, $input);

		return 0;
	}


}