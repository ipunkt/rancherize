<?php namespace Rancherize\Plugin\Loader;

use Pimple\Container;
use Rancherize\Composer\PackageNameParser;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Rancherize\Plugin\Exceptions\PluginAlreadyRegisteredException;
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
	 * @var ProjectConfiguration
	 */
	private $projectConfiguration;
	/**
	 * @var PackageNameParser
	 */
	private $packageNameParser;

	/**
	 * ComposerPluginLoader constructor.
	 * @param Configurable $configurable
	 * @param ProjectConfiguration $projectConfiguration
	 * @param PackageNameParser $packageNameParser
	 */
	public function __construct(Configurable $configurable, ProjectConfiguration $projectConfiguration, PackageNameParser $packageNameParser) {
		$this->configurable = $configurable;
		$this->projectConfiguration = $projectConfiguration;
		$this->packageNameParser = $packageNameParser;
	}

	/**
	 * @param string $pluginName
	 * @param string $classpath
	 */
	public function register( string $pluginName, string $classpath) {
		$plugins = $this->configurable->get('project.plugins');

		if( !is_array($plugins) )
			$plugins = [];

		if( in_array($classpath, $plugins) )
			throw new PluginAlreadyRegisteredException($classpath);

		$key = $this->removeVersionRestraint($pluginName);

		$plugins[$key] = $classpath;
		$this->configurable->set('project.plugins', $plugins);

		$this->projectConfiguration->save($this->configurable);
	}

	/**
	 * @param \Rancherize\Configuration\Configuration $configuration
	 * @param Application $application
	 * @param Container $container
	 */
	public function load(\Rancherize\Configuration\Configuration $configuration, Application $application, Container $container) {
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
		$packageName = $this->packageNameParser->parseName($pluginName);

		$withoutConstraints = $packageName->getProvider().'/'.$packageName->getPackageName();

		return $withoutConstraints;
	}
}