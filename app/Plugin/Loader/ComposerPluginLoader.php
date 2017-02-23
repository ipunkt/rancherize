<?php namespace Rancherize\Plugin\Loader;

use Pimple\Container;
use Rancherize\Configuration\Configurable;
use Rancherize\Plugin\Provider;
use Symfony\Component\Console\Application;

/**
 * Class ComposerPluginLoader
 */
class ComposerPluginLoader implements PluginLoader {
	/**
	 * @var Configurable
	 */
	private $configurable;

	/**
	 * ComposerPluginLoader constructor.
	 * @param Configurable $configurable
	 */
	public function __construct(Configurable $configurable) {
		$this->configurable = $configurable;
	}

	/**
	 * @param string $plugin
	 * @param string $classpath
	 */
	public function register(string $plugin, string $classpath) {
		$plugins = $this->configurable->get('plugins');

		if( !is_array($plugins) )
			$plugins = [];

		if( in_array($classpath, $plugins) )
			throw new PluginAlreadyRegisteredException($classpath);

		$this->configurable->set($plugin, $classpath);
	}

	/**
	 * @param \Rancherize\Configuration\Configuration $configuration
	 * @param Application $application
	 * @param Container $container
	 */
	public function load(\Rancherize\Configuration\Configuration $configuration, Application $application, Container $container) {
		$pluginClasspathes = $configuration->get('plugins');
		if( !is_array($pluginClasspathes) )
			$pluginClasspathes = [];

		$plugins = [];
		foreach($pluginClasspathes as $classpath) {
			$plugin = new $classpath;
			if( ! $plugin instanceof  Provider)
				continue;

			$plugin->setApplication($application);
			$plugin->setContainer($container);
			$plugin->register();

			$plugins[] = $plugin;
		}

		foreach($plugins as $plugin) {
			$plugin->boot();
		}
	}
}