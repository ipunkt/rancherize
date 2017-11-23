<?php namespace Rancherize\Plugin\Loader;

use Rancherize\Configuration\Events\ConfigurationLoadedEvent;

/**
 * Class ComposerPluginLoaderProjectConfigEventHandler
 * @package Rancherize\Plugin\Loader
 */
class ComposerPluginLoaderProjectConfigEventHandler {
	/**
	 * @var ComposerPluginLoader
	 */
	private $composerPluginLoader;

	/**
	 * ComposerPluginLoaderProjectConfigEventHandler constructor.
	 * @param ComposerPluginLoader $composerPluginLoader
	 */
	public function __construct( ComposerPluginLoader $composerPluginLoader) {
		$this->composerPluginLoader = $composerPluginLoader;
	}

	public function configurationLoaded( ConfigurationLoadedEvent $configurationLoadedEvent ) {
		$configuration = $configurationLoadedEvent->getConfiguration();

		$this->composerPluginLoader->setProjectConfig( $configuration );
	}
}