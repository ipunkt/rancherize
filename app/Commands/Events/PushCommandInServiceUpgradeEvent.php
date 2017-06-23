<?php namespace Rancherize\Commands\Events;

use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PushCommandInServiceUpgradeEvent
 * @package Rancherize\Commands\Events
 */
class PushCommandStartEvent extends Event {

	const NAME = 'push.start';

	/**
	 * @var string[]
	 */
	protected $serviceNames;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @return \string[]
	 */
	public function getServiceNames(): array {
		return $this->serviceNames;
	}

	/**
	 * @param \string[] $serviceNames
	 */
	public function setServiceNames( array $serviceNames ) {
		$this->serviceNames = $serviceNames;
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