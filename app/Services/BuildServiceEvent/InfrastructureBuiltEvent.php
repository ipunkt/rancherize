<?php namespace Rancherize\Services\BuildServiceEvent;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfrastructureBuiltEvent
 * @package Rancherize\Services\BuildServiceEvent
 */
class InfrastructureBuiltEvent extends Event {

	const NAME = 'build-service.infrastructure-built';
	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * InfrastructureBuiltEvent constructor.
	 * @param Infrastructure $infrastructure
	 * @param Configuration $configuration
	 */
	public function __construct( Infrastructure $infrastructure, Configuration $configuration) {
		$this->infrastructure = $infrastructure;
		$this->configuration = $configuration;
	}

	/**
	 * @var Infrastructure
	 */
	protected $infrastructure;

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
	 * @return Configuration
	 */
	public function getConfiguration(): Configuration {
		return $this->configuration;
	}

	/**
	 * @param Configuration $configuration
	 */
	public function setConfiguration( Configuration $configuration ) {
		$this->configuration = $configuration;
	}

}