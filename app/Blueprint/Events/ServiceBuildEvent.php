<?php namespace Rancherize\Blueprint\Events;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ServiceBuildEvent
 * @package Rancherize\Blueprint\Events
 */
class ServiceBuildEvent extends Event {

    const NAME = 'blueprint.service.built';
    /**
     * @var Infrastructure
     */
    private $infrastructure;
    /**
     * @var Service
     */
    private $service;
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * ServiceBuildEvent constructor.
     * @param Infrastructure $infrastructure
     * @param Service $service
     * @param Configuration $configuration
     */
    public function __construct(Infrastructure $infrastructure, Service $service, Configuration $configuration) {
        $this->infrastructure = $infrastructure;
        $this->service = $service;
        $this->configuration = $configuration;
    }

    /**
     * @return Infrastructure
     */
    public function getInfrastructure(): Infrastructure
    {
        return $this->infrastructure;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }


}