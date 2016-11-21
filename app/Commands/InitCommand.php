<?php namespace Rancherize\Commands;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\JsonLoader;
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
			->addArgument('blueprint', InputArgument::REQUIRED)
			->addArgument('environment', InputArgument::IS_ARRAY)
		;
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Init.');

		/**
		 * @var ArrayConfiguration
		 */
		$configuration = container('configuration');

		/**
		 * @var JsonLoader $loader
		 */
		$loader = container('loader');
		$rancherizePath = implode('', [
			getenv('PWD'),
			DIRECTORY_SEPARATOR,
			'rancherize.json'
		]);


		try {
			$loader->load($configuration, $rancherizePath);
		} catch(FileNotFoundException $e) {
			// That's okay - do nothing
		}

		$output->writeln("rancherize.json Path $rancherizePath");

		$blueprintName = $input->getArgument('blueprint');
		$environment = $input->getArgument('environment');
		var_dump($environment);

		return 0;
	}


}