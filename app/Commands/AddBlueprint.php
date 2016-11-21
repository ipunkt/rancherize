<?php namespace Rancherize\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class AddBlueprint extends Command   {

	protected function configure() {
		$this->setName('blueprint:add')
			->setDescription('Add a known blueprint')
			->addArgument('name', InputArgument::REQUIRED)
			->addArgument('classpath', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('');

		return 0;
	}


}