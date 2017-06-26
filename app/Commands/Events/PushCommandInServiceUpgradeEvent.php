<?php namespace Rancherize\Commands\Events;

use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class PushCommandInServiceUpgradeEvent
 * @package Rancherize\Commands\Events
 */
class PushCommandInServiceUpgradeEvent extends Event {

	const NAME = 'push.write';

	/**
	 * @var string[]
	 */
	protected $serviceNames;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var bool
	 */
	protected $forceUpgrade = false;

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

	/**
	 * @return bool
	 */
	public function isForceUpgrade(): bool {
		return $this->forceUpgrade;
	}

	/**
	 * @param bool $forceUpgrade
	 */
	public function setForceUpgrade( bool $forceUpgrade ) {
		$this->forceUpgrade = $forceUpgrade;
	}

}

