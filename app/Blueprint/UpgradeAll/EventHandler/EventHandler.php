<?php namespace Rancherize\Blueprint\UpgradeAll\EventHandler;

use Rancherize\Blueprint\Events\InfrastructureCompletedEvent;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;

/**
 * Class EventHandler
 * @package Rancherize\Blueprint\UpgradeAll\EventHandler
 */
class EventHandler
{

    /**
     * @var Infrastructure
     */
    protected $infrastructure;

    public function infrastructureCompleted(InfrastructureCompletedEvent $event)
    {
        $this->infrastructure = $event->getInfrastructure();
    }

    public function push(PushCommandInServiceUpgradeEvent $event)
    {
        $serviceNames = $event->getServiceNames();

        foreach ($this->infrastructure->getServices() as $service) {
            $serviceNames[] = $service->getName();
        }

        $serviceNames = array_unique($serviceNames);

        $event->setServiceNames($serviceNames);
    }

}