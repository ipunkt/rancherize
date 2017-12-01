<?php namespace Rancherize\Blueprint\Network;

use Rancherize\Blueprint\Network\DefaultNetwork\DefaultNetworkParser;
use Rancherize\Blueprint\Network\Events\NetworkEventHandler;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class NetworkProvider
 * @package Rancherize\Blueprint\Network
 */
class NetworkProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[ NetworkEventHandler::class ] = function($c) {
			return new NetworkEventHandler( $c[DefaultNetworkParser::class] );
		};

		$this->container[ DefaultNetworkParser::class ] = function() {
			return new DefaultNetworkParser();
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container[ EventDispatcher::class ];

		/**
		 * @var NetworkEventHandler $eventHandler
		 */
		$eventHandler = $this->container[ NetworkEventHandler::class ];

		$event->addListener(InfrastructureBuiltEvent::NAME, [$eventHandler, 'infrastructureBuilt'] );
	}
}