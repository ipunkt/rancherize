<?php namespace Rancherize\Blueprint\Services\Database\EventHandler;

use Rancherize\Blueprint\Services\Database\HasDatabase\HasDatabase;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;

/**
 * Class DatabasePushEventListener
 * @package Rancherize\Blueprint\Services\Database\EventHandler
 */
class DatabasePushEventListener {
	/**
	 * @var HasDatabase
	 */
	private $hasDatabase;

	/**
	 * DatabasePushEventListener constructor.
	 * @param HasDatabase $hasDatabase
	 */
	public function __construct( HasDatabase $hasDatabase) {
		$this->hasDatabase = $hasDatabase;
	}

	/**
	 * @param PushCommandStartEvent $event
	 */
	public function startNewService( PushCommandStartEvent $event ) {
		$configuration = $event->getConfiguration();

		if( !$this->hasDatabase->hasDatabase($configuration) )
			return;

		$serviceNames = $event->getServiceNames() + [ 'Database' ];
		$event->setServiceNames( $serviceNames );
	}

	/**
	 * @param PushCommandInServiceUpgradeEvent $event
	 */
	public function inServiceUpgrade( PushCommandInServiceUpgradeEvent $event ) {
		$configuration = $event->getConfiguration();

		if( !$this->hasDatabase->hasDatabase($configuration) )
			return;

		$serviceNames = $event->getServiceNames() + [ 'Database' ];
		$event->setServiceNames( $serviceNames );
	}
}