<?php namespace Rancherize\Plugin\Commands;

use Rancherize\Plugin\Traits\UsesPluginInstaller;
use Rancherize\Plugin\Traits\UsesPluginLoader;
use Symfony\Component\Console\Command\Command;

/**
 * Class PluginInstallCommand
 */
class PluginInstallCommand extends Command {

	use UsesPluginInstaller;
	use UsesPluginLoader;

	/**
	 *
	 */
	protected function configure() {
		$this->setName('plugin:install')
			->setDescription('Install a plugin for rancherize trough composer install')
			->setHelp('Uses composer require to install a composer plugin then applies plugin:register to it.')
			->addArgument('plugin name')
			;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
		$pluginName = $input->getArgument('plugin name');

		$pluginInstaller = $this->getPluginInstaller();
		$pluginInstaller->install($pluginName, $input, $output);
		$classPath = $pluginInstaller->getClasspath($pluginName);

		$pluginLoader = $this->getPluginLoader();
		$pluginLoader->register($pluginName, $classPath);
	}


}