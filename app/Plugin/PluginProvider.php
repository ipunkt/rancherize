<?php namespace Rancherize\Plugin;

use Rancherize\Configuration\Events\ConfigurationLoadedEvent;
use Rancherize\Plugin\Loader\PluginLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class PluginProvider
 * @package Rancherize\Plugin
 *
 * This provider is NOT loaded during plugin loading.
 * It is called manually in container.php
 */
class PluginProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];

		$composerPluginLoader = $this->container[PluginLoader::class];

		$event->addListener(ConfigurationLoadedEvent::NAME, [$composerPluginLoader, 'configurationLoaded']);
	}
}