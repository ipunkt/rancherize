<?php namespace Rancherize\Blueprint\Events;

use Rancherize\Blueprint\Infrastructure\Infrastructure;

/**
 * Class InfrastructureCompletedEvent
 * @package Rancherize\Blueprint\Events
 *
 * This event is sent when the infrastructure writer completes the infrastructure. Mostly filling in copying sidekicks from other services
 */
class InfrastructureCompletedEvent
{
    const NAME = 'infrastructure.completed';
    /**
     * @var Infrastructure
     */
    private $infrastructure;

    /**
     * InfrastructureCompletedEvent constructor.
     * @param Infrastructure $infrastructure
     */
    public function __construct(Infrastructure $infrastructure)
    {
        $this->infrastructure = $infrastructure;
    }

    /**
     * @return Infrastructure
     */
    public function getInfrastructure(): Infrastructure
    {
        return $this->infrastructure;
    }

}