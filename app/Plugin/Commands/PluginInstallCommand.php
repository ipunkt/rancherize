<?php namespace Rancherize\Plugin\Commands;

use Symfony\Component\Console\Command\Command;

/**
 * Class PluginInstallCommand
 */
class PluginInstallCommand extends Command {
	/**
	 *
	 */
	protected function configure() {
		$this->setName('plugin:install')
			->setDescription('Install a plugin for rancherize trough composer install')
			->setHelp('Uses composer require to install a composer plugin then applies plugin:register to it.')
			;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	}


}