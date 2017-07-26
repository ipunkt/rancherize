<?php namespace Rancherize\Blueprint\Services\Database;

use Rancherize\Blueprint\Services\Database\DatabaseBuilder\DatabaseBuilder;
use Rancherize\Blueprint\Services\Database\EventHandler\DatabasePushEventListener;
use Rancherize\Blueprint\Services\Database\HasDatabase\HasDatabase;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DatabaseProvider
 * @package Rancherize\Blueprint\Services\Database
 */
class DatabaseProvider implements \Rancherize\Plugin\Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['database-has-database'] = function() {
			return new HasDatabase();
		};

		$this->container['database-builder'] = function($c) {
			return new DatabaseBuilder( $c['database-has-database'] );
		};

		$this->container['database-push-listener'] = function($c) {
			return new DatabasePushEventListener( $c['database-has-database'] );
		};
	}

	/**
	 */
	public function boot() {
		$event = $this->container['event'];
		$pushListener = $this->container['database-push-listener'];
		$event->addListener(PushCommandInServiceUpgradeEvent::NAME, [$pushListener, 'inServiceUpgrade']);
		$event->addListener(PushCommandStartEvent::NAME, [$pushListener, 'startNewService']);
	}
}