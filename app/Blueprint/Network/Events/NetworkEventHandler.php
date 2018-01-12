<?php namespace Rancherize\Blueprint\Network\Events;

use Rancherize\Blueprint\Network\DefaultNetwork\DefaultNetworkParser;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;

/**
 * Class NetworkEventHandler
 * @package Rancherize\Blueprint\Network\Events
 */
class NetworkEventHandler {
	/**
	 * @var DefaultNetworkParser
	 */
	private $defaultNetworkParser;

	/**
	 * NetworkEventHandler constructor.
	 * @param DefaultNetworkParser $defaultNetworkParser
	 */
	public function __construct( DefaultNetworkParser $defaultNetworkParser) {
		$this->defaultNetworkParser = $defaultNetworkParser;
	}

	/**
	 * @param InfrastructureBuiltEvent $event
	 */
	public function infrastructureBuilt( InfrastructureBuiltEvent $event ) {

		$this->defaultNetworkParser->parse($event->getConfiguration(), $event->getInfrastructure());

	}
}