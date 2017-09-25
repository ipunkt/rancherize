<?php namespace Rancherize\Blueprint\Commands;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * Add the given blueprint name and classpath to the known blueprints
 */
class BlueprintAdd extends Command   {
	/**
	 * @var BlueprintFactory
	 */
	private $blueprintFactory;

	/**
	 * BlueprintAdd constructor.
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
		$this->setName('blueprint:add')
			->setDescription('Add a known blueprint')
			->addArgument('name', InputArgument::REQUIRED)
			->addArgument('classpath', InputArgument::REQUIRED)
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



		$name = $input->getArgument('name');
		$classpath = $input->getArgument('classpath');
		$configuration->set('project.blueprints.'.$name, $classpath);

		/**
		 * @var BlueprintFactory $blueprintFactory
		 */
		$this->blueprintFactory->add($name, $classpath);

		/**
		 * TODO: logical combination of add -> projectConfig->save
		 */

		$projectConfig->save($configuration);

		return 0;
	}


}