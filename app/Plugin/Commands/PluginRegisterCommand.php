<?php namespace Rancherize\Plugin\Commands;

use Symfony\Component\Console\Command\Command;

/**
 * Class PluginRegisterCommand
 */
class PluginRegisterCommand extends Command {

	/**
	 *
	 */
	protected function configure() {
		$this->setName('plugin:register')
			->setDescription('Register an already installed composer plugin to be loaded into rancherize')
			->setHelp('sets rancherize.json plugins.VENDOR_PACKAGE to package/composer.json extra.provider - a Classpath to the ServiceProvider which initializes the plugin')
			;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	}


}