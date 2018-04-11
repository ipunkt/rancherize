<?php namespace Rancherize\Configuration\Events;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EnvironmentConfigurationLoadedEvent
 * @package Rancherize\Configuration\Events
 */
class EnvironmentConfigurationLoadedEvent extends Event {

	const NAME = 'configuration.environment.loaded';

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var Configuration
	 */
	protected $environmentConfiguration;

	/**
	 * @return Configurable
	 */
	public function getConfiguration(): Configuration {
		return $this->configuration;
	}

	/**
	 * @param Configurable $configuration
	 */
	public function setConfiguration( Configuration $configuration ) {
		$this->configuration = $configuration;
	}

	/**
	 * @return Configurable
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