<?php namespace Rancherize\Blueprint\UpgradeAll;

use Rancherize\Blueprint\Events\InfrastructureCompletedEvent;
use Rancherize\Blueprint\UpgradeAll\EventHandler\EventHandler;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Provider
 * @package Rancherize\Blueprint\UpgradeAll
 */
class Provider implements \Rancherize\Plugin\Provider
{
    use ProviderTrait;

    /**
     */
    public function register()
    {
        $this->container[EventHandler::class] = function () {
            return new EventHandler;
        };
    }

    /**
     */
    public function boot()
    {
        /**
         * @var EventDispatcher $event
         */
        $event = $this->container[EventDispatcher::class];

        $handler = $this->container[EventHandler::class];
        $event->addListener(PushCommandInServiceUpgradeEvent::NAME, [$handler, 'push']);
        $event->addListener(InfrastructureCompletedEvent::NAME, [$handler, 'infrastructureCompleted']);
    }
}