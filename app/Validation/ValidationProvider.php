<?php namespace Rancherize\Validation;

use Rancherize\Events\ValidatingEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Validation\ForceResourceLimits\EventHandler as ForceResourceLimitEventHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ValidationProvider
 * @package Rancherize\Validation
 */
class ValidationProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[ForceResourceLimitEventHandler::class] = function () {
			return new ForceResourceLimitEventHandler();
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var ForceResourceLimitEventHandler $eventHandler
		 */
		$eventHandler = $this->container[ForceResourceLimitEventHandler::class];

		if ( !empty( getenv( 'REQUIRE_RESOURCE_LIMIT' ) ) )
			$eventHandler->setForceResourceLimits( true, 'Environment REQUIRE_RESOURCE_LIMIT' );

		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container[EventDispatcher::class];
		$event->addListener(ValidatingEvent::NAME, [$eventHandler, 'validate']);
	}
}