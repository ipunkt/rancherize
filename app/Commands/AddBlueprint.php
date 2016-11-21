<?php namespace Rancherize\Commands;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\Writer\Writer;
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

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * @var Loader $loader
		 */
		$loader = container('loader');

		$rancherizePath = implode('', [
			getenv('PWD'),
			DIRECTORY_SEPARATOR,
			'rancherize.json'
		]);

		try{
			$loader->load($configuration, $rancherizePath);
		} catch(FileNotFoundException $e) {
			// Fine, do nothing
		}


		$name = $input->getArgument('name');
		$classpath = $input->getArgument('classpath');
		$configuration->set('project.blueprints.'.$name, $classpath);

		/**
		 * @var Writer $writer
		 */
		$writer = container('writer');
		$writer->write($configuration, $rancherizePath);

		return 0;
	}


}