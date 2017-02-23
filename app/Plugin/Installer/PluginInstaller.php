<?php namespace Rancherize\Plugin\Installer;

/**
 * Interface PluginInstaller
 */
interface PluginInstaller {

	/**
	 * @param $name
	 * @return mixed
	 */
	function install($name);

	/**
	 * @param $name
	 * @return mixed
	 */
	function getClasspath($name);

}