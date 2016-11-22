<?php namespace Rancherize\Commands;
use Rancherize\Blueprint\Infrastructure\InfrastructureWriter;
use Rancherize\Blueprint\Traits\LoadsBlueprintTrait;
use Rancherize\Commands\Traits\BuildsTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\File\FileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 */
class StopCommand extends Command   {

	use BuildsTrait;

	protected function configure() {
		$this->setName('stop')
			->setDescription('Start an environment on the local machine')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$this->getBuildService()->build($environment, $input);

		passthru('docker-compose -f ./.rancherize/docker-compose.yml stop');

		return 0;
	}


}