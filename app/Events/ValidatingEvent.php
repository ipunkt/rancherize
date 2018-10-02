<?php namespace Rancherize\Events;

use Rancherize\Blueprint\Blueprint;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ValidatingEvent
 * @package Rancherize\Events
 */
class ValidatingEvent extends Event {

	const NAME = 'validating';

	/**
	 * @var string
	 */
	protected $environment;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var Blueprint
	 */
	protected $blueprint;

	/**
	 * @return ValidatingEvent
	 */
	static public function make() {
		return new static();
	}

	/**
	 * @return string
	 */
	public function getEnvironment(): string {
		return $this->environment;
	}

	/**
	 * @param string $environment
	 * @return ValidatingEvent
	 */
	public function setEnvironment( string $environment ): ValidatingEvent {
		$this->environment = $environment;
		return $this;
	}

	/**
	 * @return Configuration
	 */
	public function getConfiguration(): Configuration {
		return $this->configuration;
	}

	/**
	 * @param Configuration $configuration
	 * @return ValidatingEvent
	 */
	public function setConfiguration( Configuration $configuration ): ValidatingEvent {
		$this->configuration = $configuration;
		return $this;
	}

	/**
	 * @return Blueprint
	 */
	public function getBlueprint(): Blueprint {
		return $this->blueprint;
	}

	/**
	 * @param Blueprint $blueprint
	 * @return ValidatingEvent
	 */
	public function setBlueprint( Blueprint $blueprint ): ValidatingEvent {
		$this->blueprint = $blueprint;
		return $this;
	}

}