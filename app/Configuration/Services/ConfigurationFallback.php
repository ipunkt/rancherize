<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configuration;

/**
 * Class ConfigurationFallback
 * @package Rancherize\Configuration\Services
 */
class ConfigurationFallback implements Configuration {
	/**
	 * @var Configuration
	 */
	protected $configuration;
	/**
	 * @var Configuration
	 */
	protected $fallback;

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
	 * @param string $key
	 * @param null $default
	 * @return mixed
	 */
	public function get(string $key = null, $default = null) {
		return $this->configuration->get( $key, $this->fallback->get($key, $default) );
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function has(string $key) : bool {
		return $this->configuration->has($key) || $this->fallback->has($key);
	}
}