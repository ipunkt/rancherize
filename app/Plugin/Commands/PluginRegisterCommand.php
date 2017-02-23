<?php namespace Rancherize\Plugin\Commands;

use Rancherize\Plugin\Traits\UsesPluginInstaller;
use Rancherize\Plugin\Traits\UsesPluginLoader;
use Symfony\Component\Console\Command\Command;

/**
 * Class PluginRegisterCommand
 */
class PluginRegisterCommand extends Command {

	use UsesPluginInstaller;
	use UsesPluginLoader;

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

		$pluginInstaller = $this->getPluginInstaller();
		$pluginLoader = $this->getPluginLoader();

		$classPath = $pluginInstaller->getClasspath($pluginName);
		$pluginLoader->register($classPath);


	}


}