<?php namespace Rancherize\Blueprint\Infrastructure\Service\Events;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\ServiceYamlDefinition;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ServiceWriterServicePreparedEvent
 * @package Rancherize\Blueprint\Infrastructure\Service\Events
 */
class ServiceWriterWritePreparedEvent extends Event {

	const NAME = 'service-writer.prepared-to-write';

	/**
	 * @var Service
	 */
	private $service;

	/**
	 * @var int
	 */
	private $fileVersion = 2;
    /**
     * @var ServiceYamlDefinition
     */
    private $definition;

    /**
     *
     * /**
     * ServiceWriterServicePreparedEvent constructor.
     * @param Service $service
     * @param ServiceYamlDefinition $definition
     */
	public function __construct( Service $service, ServiceYamlDefinition $definition ) {
        $this->service = $service;
        $this->definition = $definition;
    }

	/**
	 * @return Service
	 */
	public function getService() {
		return $this->service;
	}

    /**
     * @return int
     */
    public function getFileVersion(): int
    {
        return $this->fileVersion;
    }

    /**
     * @return ServiceYamlDefinition
     */
    public function getDefinition(): ServiceYamlDefinition
    {
        return $this->definition;
    }

}