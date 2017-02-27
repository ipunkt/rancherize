<?php namespace Rancherize\Plugin\Loader;

use Pimple\Container;
use Rancherize\Configuration\Configuration;
use Symfony\Component\Console\Application;

/**
 * Interface PluginLoader
 */
interface PluginLoader {

	/**
	 * @param string $plugin
	 * @param string $classpath
	 * @return
	 */
	function register(string $plugin, string $classpath);

	/**
	 * @param Configuration $configuration
	 * @param Application $application
	 * @param Container $container
	 * @return
	 */
	function load(Configuration $configuration, Application $application, Container $container);
}