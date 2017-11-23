<?php namespace Rancherize\Plugin\Commands;

use Rancherize\Plugin\Installer\PluginInstaller;
use Rancherize\Plugin\Loader\PluginLoader;
use Symfony\Component\Console\Command\Command;

/**
 * Class PluginInstallCommand
 */
class PluginInstallCommand extends Command {

	/**
	 * @var PluginInstaller
	 */
	private $pluginInstaller;
	/**
	 * @var PluginLoader
	 */
	private $pluginLoader;

	/**
	 * PluginInstallCommand constructor.
	 * @param PluginInstaller $pluginInstaller
	 * @param PluginLoader $pluginLoader
	 */
	public function __construct(  PluginLoader $pluginLoader, PluginInstaller $pluginInstaller ) {
		parent::__construct();
		$this->pluginInstaller = $pluginInstaller;
		$this->pluginLoader = $pluginLoader;
	}

	/**
	 *
	 */
	protected function configure() {
		$this->setName('plugin:install')
			->setDescription('Install a plugin for rancherize through composer install')
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

		$this->pluginInstaller->install($pluginName, $input, $output);
		$classPath = $this->pluginInstaller->getClasspath($pluginName);

		$this->pluginLoader->register($pluginName, $classPath);
	}


}
