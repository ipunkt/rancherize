<?php namespace Rancherize\Plugin\Loader;

use Rancherize\Configuration\Configuration;

/**
 * Interface PluginLoader
 */
interface PluginLoader {

	/**
	 * @param string $classpath
	 * @return
	 */
	function register(string $classpath);

	/**
	 * @param Configuration $configuration
	 * @return
	 */
	function load(Configuration $configuration);
}