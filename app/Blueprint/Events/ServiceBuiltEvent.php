<?php namespace Rancherize\Blueprint\Events;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ServiceBuiltEvent
 * @package Rancherize\Blueprint\Events
 */
class ServiceBuiltEvent extends Event
{

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
    private $commandConfiguration;
    /**
     * @var Configuration
     */
    private $environmentConfiguration;

    /**
     * ServiceBuiltEvent constructor.
     * @param Infrastructure $infrastructure
     * @param Service $service
     * @param Configuration $commandConfiguration
     * @param Configuration $environmentConfiguration
     */
    public function __construct(Infrastructure $infrastructure, Service $service, Configuration $commandConfiguration, Configuration $environmentConfiguration)
    {
        $this->infrastructure = $infrastructure;
        $this->service = $service;
        $this->commandConfiguration = $commandConfiguration;
        $this->environmentConfiguration = $environmentConfiguration;
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
        return $this->commandConfiguration;
    }


    /**
     * @return Configuration
     */
    public function getCommandConfiguration(): Configuration
    {
        return $this->commandConfiguration;
    }

}