<?php namespace Rancherize\Blueprint\Events;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MainServiceBuiltEvent
 * @package Rancherize\Blueprint\Events
 */
class MainServiceBuiltEvent extends Event {

	const NAME = 'blueprint.main-service.built';
	/**
	 * @var Infrastructure
	 */
	private $infrastructure;
	/**
	 * @var Service
	 */
	private $mainService;
	/**
	 * @var Configuration
	 */
	private $environmentConfiguration;

	/**
	 * MainServiceBuiltEvent constructor.
	 * @param Infrastructure $infrastructure
	 * @param Service $mainService
	 * @param Configuration $environmentConfiguration
	 */
	public function __construct( Infrastructure $infrastructure, Service $mainService, Configuration $environmentConfiguration) {
		$this->infrastructure = $infrastructure;
		$this->mainService = $mainService;
		$this->environmentConfiguration = $environmentConfiguration;
	}

	/**
	 * @return Infrastructure
	 */
	public function getInfrastructure(): Infrastructure {
		return $this->infrastructure;
	}

	/**
	 * @return Service
	 */
	public function getMainService(): Service {
		return $this->mainService;
	}

	/**
	 * @return Configuration
	 */
	public function getEnvironmentConfiguration(): Configuration {
		return $this->environmentConfiguration;
	}

}