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
		$container = $this->container;

		$container[\Rancherize\Plugin\Loader\PluginLoader::class] = function() {

			/*
			 * project-config is not set in this file - it is set in the rancherize.php once the project config was loaded for
			 * use with the plugin system
			 */
			return new \Rancherize\Plugin\Loader\ComposerPluginLoader();
		};

		/**
		 * TODO: Move to different provider
		 */
		$container->extend(\Rancherize\Plugin\Loader\PluginLoader::class, function($pluginLoader, $c) {

			/**
			 * @var \Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator $extraPluginLoader
			 */
			$extraPluginLoader = $c[\Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator::class];

			$extraPluginLoader->setPluginLoader($pluginLoader);

			return $extraPluginLoader;

		});

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