<?php namespace Rancherize\Configuration;

use Rancherize\Configuration\ArrayAdder\ArrayAdder;
use Rancherize\Configuration\EventHandlers\LoadConfigurationForCommandEventHandler;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ConfigurationProvider
 * @package Rancherize\Configuration
 */
class ConfigurationProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['config-array-adder'] = function() {
			return new ArrayAdder();
		};


		$this->container['config.load-configurationf-for-command-event-handler'] = function($c) {
			return new LoadConfigurationForCommandEventHandler( $c['event'], $c['config-wrapper']);
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $eventDispatcher
		 */
		$eventDispatcher = $this->container['event'];
		$eventHandler = $this->container['config.load-configurationf-for-command-event-handler'];

		$eventDispatcher->addListener(ConsoleEvents::COMMAND, [$eventHandler, 'prepareCommand']);
	}
}