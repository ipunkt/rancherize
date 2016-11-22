<?php namespace Rancherize\Commands;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Services\GlobalConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RancherAccessCommand
 * @package Rancherize\Commands
 */
class RancherAccessCommand extends Command {

	protected function configure() {
		$this->setName('rancher:access')
			->setDescription('Initialize Rancher access')
		;
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * @var GlobalConfiguration $globalConfiguration
		 */
		$globalConfiguration = container('global-config-service');

		try {
			$globalConfiguration->load($configuration);
		} catch(FileNotFoundException $e) {
			$configuration->set('global.rancher', [
				'default' => [
					'url' => 'http://rancher:8080/api/v1',
					'key' => 'key',
					'secret' => 'secret',
				]
			]);
			$globalConfiguration->save($configuration);
		}
		$configPath = $globalConfiguration->getPath();

		$editor = getenv('EDITOR') ?: "vim";
		$returnValue = 0;
		passthru("$editor '$configPath'", $returnValue);
	}
}