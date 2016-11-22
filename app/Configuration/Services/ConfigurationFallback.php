<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configuration;

/**
 * Class ConfigurationFallback
 * @package Rancherize\Configuration\Services
 */
class ConfigurationFallback {
	/**
	 * @var Configuration
	 */
	private $configuration;
	/**
	 * @var Configuration
	 */
	private $fallback;

	/**
	 * ConfigurationFallback constructor.
	 * @param Configuration $configuration
	 * @param Configuration $fallback
	 */
	public function __construct(Configuration $configuration, Configuration $fallback) {
		$this->configuration = $configuration;
		$this->fallback = $fallback;
	}

	/**
	 * @param $key
	 */
	public function get($key) {
		return $this->configuration->get( $key, $this->fallback->get($key) );
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function has($key) {
		return $this->configuration->has($key) || $this->fallback->has($key);
	}
}