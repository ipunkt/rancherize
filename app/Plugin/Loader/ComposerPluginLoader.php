<?php namespace Rancherize\Plugin\Loader;

use Pimple\Container;
use Rancherize\Composer\PackageNameParser;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Rancherize\Exceptions\PluginAlreadyRegisteredException;
use Rancherize\Plugin\Provider;
use Symfony\Component\Console\Application;

/**
 * Class ComposerPluginLoader
 *
 * A plugin loader using the project rancherize.json
 * Note that is does its own loading of the configuration because it is outside of the normal use and the normal project
 * configuration
 *
 * NOTE: Dependencies can NOT be injected because they are not yet loaded when creating the loader but they will be available once it is run.
 */
class ComposerPluginLoader implements PluginLoader {

	/**
	 * @var ProjectConfiguration
	 */
	private $projectConfiguration = null;

	/**
	 * @param string $pluginName
	 * @param string $classpath
	 */
	public function register( string $pluginName, string $classpath) {
		$configurable = $this->loadProjectConfiguration( container() );

		$plugins = $configurable->get('project.plugins');

		if( !is_array($plugins) )
			$plugins = [];

		if( in_array($classpath, $plugins) )
			throw new PluginAlreadyRegisteredException($classpath);

		$key = $this->removeVersionRestraint($pluginName);

		$plugins[$key] = $classpath;
		$configurable->set('project.plugins', $plugins);

		$this->projectConfiguration->save($configurable);
	}

	/**
	 * @param Application $application
	 * @param Container $container
	 */
	public function load( Application $application, Container $container) {
		$configuration = $this->loadProjectConfiguration( $container );

		$pluginClasspathes = $configuration->get('project.plugins');
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

	/**
	 * @param $pluginName
	 * @return string
	 */
	private function removeVersionRestraint( $pluginName ) {
		/**
		 * @var PackageNameParser $packageNameParser
		 */
		$packageNameParser = container(PackageNameParser::class);

		$packageName = $packageNameParser->parseName($pluginName);

		$withoutConstraints = $packageName->getProvider().'/'.$packageName->getPackageName();

		return $withoutConstraints;
	}

	/**
	 * @param Container $container
	 * @return Configurable
	 */
	protected function loadProjectConfiguration( Container $container ): Configurable {
		if($this->projectConfiguration === null)
			$this->projectConfiguration = container( ProjectConfiguration::class );

		/**
		 * @var \Rancherize\Configuration\Configurable $configuration
		 */
		$configuration = $container['configuration'];
		$configuration = $this->projectConfiguration->load( $configuration );
		return $configuration;
	}
}