<?php namespace Rancherize\Plugin\Commands;

use Rancherize\Plugin\Installer\PluginInstaller;
use Rancherize\Plugin\Loader\PluginLoader;
use Symfony\Component\Console\Command\Command;

/**
 * Class PluginRegisterCommand
 */
class PluginRegisterCommand extends Command {
	/**
	 * @var PluginLoader
	 */
	private $pluginLoader;
	/**
	 * @var PluginInstaller
	 */
	private $pluginInstaller;

	/**
	 * PluginRegisterCommand constructor.
	 * @param PluginLoader $pluginLoader
	 * @param PluginInstaller $pluginInstaller
	 */
	public function __construct( PluginLoader $pluginLoader, PluginInstaller $pluginInstaller) {
		$this->pluginLoader = $pluginLoader;
		$this->pluginInstaller = $pluginInstaller;
		parent::__construct();
	}

	/**
	 *
	 */
	protected function configure() {
		$this->setName('plugin:register')
			->setDescription('Register an already installed composer plugin to be loaded into rancherize')
			->setHelp('sets rancherize.json plugins.VENDOR_PACKAGE to package/composer.json extra.provider - a Classpath to the ServiceProvider which initializes the plugin')
			->addArgument('plugin name')
			;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
		$pluginName = $input->getArgument('plugin name');


		$classPath = $this->pluginInstaller->getClasspath($pluginName);
		$this->pluginLoader->register($pluginName, $classPath);


	}


}