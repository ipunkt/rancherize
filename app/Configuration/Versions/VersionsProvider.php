<?php namespace Rancherize\Configuration\Versions;

use Rancherize\Commands\Events\InitCommandEvent;
use Rancherize\Configuration\Events\ConfigurationLoadedEvent;
use Rancherize\Configuration\Versions\StaticVersion\ConfigurationEventHandler;
use Rancherize\Configuration\Versions\StaticVersion\StaticConfigurationVersionService;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class VersionsProvider
 * @package Rancherize\Configuration\Versions
 */
class VersionsProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['configuration-event-handler'] = function() {
			return new ConfigurationEventHandler();
		};

		$this->container['static-configuration-version'] = function() {
			$service = new StaticConfigurationVersionService();

			return $service;
		};

		$this->container['configuration-version'] = function($c) {
			return $c['static-configuration-version'];
		};

		$this->container[DefaultVersionSetter::class] = function() {
			return DefaultVersionSetter::class;
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event  = $this->container['event'];

		/**
		 * @var ConfigurationEventHandler $listener
		 */
		$listener = $this->container['configuration-event-handler'];
		$listener->setStaticConfigurationService( $this->container['static-configuration-version'] );

		$event->addListener(ConfigurationLoadedEvent::NAME, [$listener, 'configurationLoaded']);

		/**
		 * @var DefaultVersionSetter $defaultVersionSetter
		 */
		$defaultVersionSetter = $this->container[DefaultVersionSetter::class];
		$event->addListener(InitCommandEvent::NAME, [$defaultVersionSetter, 'initEvent']);
	}
}