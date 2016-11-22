<?php namespace Rancherize\Blueprint\Commands;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlueprintList
 * @package Rancherize\Commands
 */
class BlueprintList extends Command {

	/**
	 *
	 */
	protected function configure() {
		$this->setName('blueprint:list')
			->setDescription('List all known Blueprints');
		;
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		/**
		 * @var ProjectConfiguration $projectConfig
		 */
		$projectConfig = container('project-config-service');
		$configuration = container('configuration');
		$configuration = $projectConfig->load($configuration);
		container()->offsetSet('project-configuration', $configuration);

		/**
		 * @var BlueprintFactory $blueprintFactory
		 */
		$blueprintFactory = container('blueprint-factory');
		$blueprints = $blueprintFactory->available();

		$output->writeln([
			'Available Blueprints',
			'====================',
			''
		]);
		foreach($blueprints as $blueprint) {
			$output->writeln("- $blueprint");
		}

		return 0;
	}

}