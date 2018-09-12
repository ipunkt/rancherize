<?php namespace Rancherize\Blueprint\Events;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AppServiceEvent
 * @package Rancherize\Blueprint\Events
 */
class AppServiceEvent extends Event {

	const NAME = 'blueprint.app-service.built';
	/**
	 * @var Infrastructure
	 */
	private $infrastructure;
	/**
	 * @var Service
	 */
	private $appService;
	/**
	 * @var Configuration
	 */
	private $environmentConfiguration;

	public function __construct(Infrastructure $infrastructure, Service $appService, Configuration $environmentConfiguration) {
		$this->infrastructure = $infrastructure;
		$this->appService = $appService;
		$this->environmentConfiguration = $environmentConfiguration;
	}

	/**
	 * @return Infrastructure
	 */
	public function getInfrastructure(): Infrastructure {
		return $this->infrastructure;
	}

	/**
	 * @param Infrastructure $infrastructure
	 */
	public function setInfrastructure( Infrastructure $infrastructure ) {
		$this->infrastructure = $infrastructure;
	}

	/**
	 * @return Service
	 */
	public function getAppService(): Service {
		return $this->appService;
	}

	/**
	 * @param Service $appService
	 */
	public function setAppService( Service $appService ) {
		$this->appService = $appService;
	}

	/**
	 * @return Configuration
	 */
	public function getEnvironmentConfiguration(): Configuration {
		return $this->environmentConfiguration;
	}

	/**
	 * @param Configuration $environmentConfiguration
	 */
	public function setEnvironmentConfiguration( Configuration $environmentConfiguration ) {
		$this->environmentConfiguration = $environmentConfiguration;
	}

}