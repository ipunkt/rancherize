<?php namespace Rancherize\Configuration\Traits;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Events\ConfigurationLoadedEvent;
use Rancherize\Configuration\Services\ConfigWrapper;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Trait LoadsConfigurationTrait
 * @package Rancherize\Configuration\Traits
 *
 * Loads the global and the project configuration
 */
trait LoadsConfigurationTrait {

	/**
	 * @return Configurable
	 *
	 * @TODO: Move into ConsoleVents::COMMAND Event - if $command instanceOf RequiresConfiguration - load config
	 */
	private function loadConfiguration() {

		/**
		 * @var ConfigWrapper $configWrapper
		 */
		$configWrapper = container('config-wrapper');
		$config = $configWrapper->configuration();

		$configWrapper->loadGlobalConfig($config);
		$configWrapper->loadProjectConfig($config);

		/**
		 * @var EventDispatcher $eventSystem
		 */
		$eventSystem = container('event');
		$event = new ConfigurationLoadedEvent();
		$event->setConfiguration($config);
		$eventSystem->dispatch($event::NAME, $event);

		return $config;
	}
}