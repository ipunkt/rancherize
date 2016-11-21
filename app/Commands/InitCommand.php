<?php namespace Rancherize\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Rancherize\Commands
 */
class InitCommand extends Command {

	protected function configure() {
		$this->setName('init')
			->setDescription('Initialize all given arguments')
			->addArgument('environment', InputArgument::IS_ARRAY)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Init.');

		$environment = $input->getArgument('environment');
		var_dump($environment);

		return 0;
	}


}