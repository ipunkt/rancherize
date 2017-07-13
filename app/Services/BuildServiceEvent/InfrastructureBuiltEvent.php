<?php namespace Rancherize\Services\BuildServiceEvent;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfrastructureBuiltEvent
 * @package Rancherize\Services\BuildServiceEvent
 */
class InfrastructureBuiltEvent extends Event {

	const NAME = 'build-service.infrastructure-built';

	/**
	 * InfrastructureBuiltEvent constructor.
	 * @param Infrastructure $infrastructure
	 */
	public function __construct( Infrastructure $infrastructure) {
		$this->infrastructure = $infrastructure;
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

}