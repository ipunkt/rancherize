<?php namespace Rancherize\Blueprint\Commands;

use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Configurable;
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
	 * @var ProjectConfiguration
	 */
	private $projectConfiguration;

	/**
	 * @var Configurable
	 */
	private $configurable;

	/**
	 * BlueprintList constructor.
	 * @param BlueprintFactory $blueprintFactory
	 * @param ProjectConfiguration $projectConfiguration
	 * @param Configurable $configurable
	 * @internal param Configuration $configuration
	 */
	public function __construct( BlueprintFactory $blueprintFactory, ProjectConfiguration $projectConfiguration, Configurable $configurable) {
		parent::__construct();
		$this->blueprintFactory = $blueprintFactory;
		$this->projectConfiguration = $projectConfiguration;
		$this->configurable = $configurable;
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