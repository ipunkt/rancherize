<?php namespace Rancherize\Commands;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Rancherize\Commands
 */
class InitCommand extends ContainerAwareCommand {

	protected function configure() {
		$this->setName('init')
			->setDescription('Initialize all given arguments')
			->addArgument('blueprint', InputArgument::REQUIRED)
			->addArgument('environment', InputArgument::IS_ARRAY)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Init.');

		$blueprintName = $input->getArgument('blueprint');
		$environment = $input->getArgument('environment');
		var_dump($environment);

		return 0;
	}


}