<?php namespace Rancherize\Configuration\Events;

use Rancherize\Configuration\Configurable;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ConfigurationLoadedEvent
 * @package Rancherize\Configuration\Events
 */
class ConfigurationLoadedEvent extends Event {

	const NAME = 'configuration.loaded';

	/**
	 * @var Configurable
	 */
	protected $configuration;

	/**
	 * @return Configurable
	 */
	public function getConfiguration(): Configurable {
		return $this->configuration;
	}

	/**
	 * @param Configurable $configuration
	 */
	public function setConfiguration( Configurable $configuration ) {
		$this->configuration = $configuration;
	}

}