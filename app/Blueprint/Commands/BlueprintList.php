<?php namespace Rancherize\Blueprint\Commands;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BlueprintList
 * @package Rancherize\Commands
 *
 * List all known blueprints
 */
class BlueprintList extends Command {
	/**
	 * @var BlueprintFactory
	 */
	private $blueprintFactory;

	/**
	 * BlueprintList constructor.
	 * @param BlueprintFactory $blueprintFactory
	 */
	public function __construct( BlueprintFactory $blueprintFactory) {
		parent::__construct();
		$this->blueprintFactory = $blueprintFactory;
	}

	/**
	 *
	 */
	protected function configure() {
		$this->setName('blueprint:list')
			->setDescription('List all known blueprints');
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

		$blueprints = $this->blueprintFactory->available();

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