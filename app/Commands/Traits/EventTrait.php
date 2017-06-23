<?php namespace Rancherize\Commands\Traits;

use Symfony\Component\EventDispatcher\EventDispatcher;

trait EventTrait {

	/**
	 * @return EventDispatcher
	 */
	public function getEventDispatcher() : EventDispatcher {
		return container('event');
	}

}