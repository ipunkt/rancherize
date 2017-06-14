<?php namespace Rancherize\Plugin\Loader;
use Pimple\Container;
use Rancherize\Configuration\Configuration;
use Rancherize\Plugin\Provider;
use Symfony\Component\Console\Application;

/**
 * Class ExtraPluginLoaderDecorator
 * @package Rancherize\Plugin\Loader
 *
 * A decorator used to load a list of extra plugins outisde of the usual configuration
 *
 * use-case: Rancherize will be moved to use the plugin system to structure its internal code.
 * These internal plugins depend on the rancherize version so they should not appear in the rancherize.json - thus registering
 * with this decorator instead of the ComplusePluginLoader
 */
class ExtraPluginLoaderDecorator implements PluginLoader {

	/**
	 * @var PluginLoader
	 */
	private $pluginLoader;

	/**
	 * @var string[]
	 */
	private $pluginList = [];
	/**
	 * @var Loader
	 */
	private $loader;

	/**
	 * ExtraPluginLoaderDecorator constructor.
	 * @param Loader $loader
	 */
	public function __construct( Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @param PluginLoader $pluginLoader
	 */
	public function setPluginLoader( PluginLoader $pluginLoader ) {
		$this->pluginLoader = $pluginLoader;
	}

	/**
	 * @param string $plugin
	 * @param string $classpath
	 * @return
	 */
	public function register( string $plugin, string $classpath ) {
		return $this->pluginLoader->register( $plugin, $classpath );
	}

	/**
	 * @param Configuration $configuration
	 * @param Application $application
	 * @param Container $container
	 * @return
	 */
	public function load( Configuration $configuration, Application $application, Container $container ) {

		$extraPlugins = $this->createExtraPlugins($application, $container);

		foreach($extraPlugins as $extraPlugin)
			$extraPlugin->register();

		$success = $this->pluginLoader->load($configuration, $application, $container);

		foreach($extraPlugins as $extraPlugin)
			$extraPlugin->boot();

		return $success;
	}

	/**
	 * @param string $classPath
	 */
	public function registerExtra($classPath) {
		$this->pluginList[$classPath] = $classPath;
	}

	/**
	 * @param Application $application
	 * @param Container $container
	 * @return Provider[]
	 */
	private function createExtraPlugins(Application $application, Container $container) {
		$extraPlugins = [];

		/**
		 *
		 */
		foreach($this->pluginList as $pluginClassPath) {
			/**
			 * @var Provider $plugin
			 */
			$plugin = $this->loader->load( $pluginClassPath );
			$plugin->setApplication($application);
			$plugin->setContainer($container);

			$extraPlugins[] = $plugin;

		}

		return $extraPlugins;
	}
}