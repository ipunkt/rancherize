<?php namespace Rancherize\Validation;

use Rancherize\Events\ValidatingEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Validation\ForceResourceLimits\EventHandler as ForceResourceLimitEventHandler;
use Rancherize\Validation\ServiceNameValidation\EventHandler as ServiceNameEventHandler;
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
		$this->container[ServiceNameEventHandler::class] = function() {
		    return new ServiceNameEventHandler();
        };
	}

	/**
	 */
	public function boot() {
        $event = $this->container[EventDispatcher::class];

		/**
		 * @var ForceResourceLimitEventHandler $eventHandler
		 */
		$eventHandler = $this->container[ForceResourceLimitEventHandler::class];

		if ( !empty( getenv( 'REQUIRE_RESOURCE_LIMIT' ) ) )
			$eventHandler->setForceResourceLimits( true, 'Environment REQUIRE_RESOURCE_LIMIT' );

		/**
		 * @var EventDispatcher $event
		 */
		$event->addListener(ValidatingEvent::NAME, [$eventHandler, 'validate']);

        /**
         * @var ForceResourceLimitEventHandler $eventHandler
         */
        $serviceNameEventHandler = $this->container[ServiceNameEventHandler::class];
        $event->addListener(ValidatingEvent::NAME, [$serviceNameEventHandler, 'validate']);
	}
}