<?php namespace Rancherize\Plugin\Loader;

use Pimple\Container;
use Symfony\Component\Console\Application;

/**
 * Interface PluginLoader
 */
interface PluginLoader {

	/**
	 * @param string $pluginName
	 * @param string $classpath
	 * @return
	 */
	function register( string $pluginName, string $classpath);

	/**
	 * @param Application $application
	 * @param Container $container
	 * @return
	 */
	function load(Application $application, Container $container);
}