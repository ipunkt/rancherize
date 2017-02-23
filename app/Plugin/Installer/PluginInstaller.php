<?php namespace Rancherize\Plugin\Installer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface PluginInstaller
 */
interface PluginInstaller {

	/**
	 * @param $name
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return
	 */
	function install($name, InputInterface $input, OutputInterface $output);

	/**
	 * @param $name
	 * @return mixed
	 */
	function getClasspath($name);

}