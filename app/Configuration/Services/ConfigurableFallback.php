<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;

/**
 * Class ConfigurableFallback
 * @package Rancherize\Configuration\Services
 *
 * Extends the ConfigurationFallback to be usable with set.
 * It sets the keys on the primary configuration
 */
class ConfigurableFallback extends ConfigurationFallback implements Configurable {

	/**
	 * @var Configurable
	 */
	protected $configuration;
	/**
	 * @var Configurable
	 */
	protected $fallback;

	/**
	 * ConfigurationFallback constructor.
	 * @param Configurable $configuration
	 * @param Configurable $fallback
	 */
	public function __construct(Configurable $configuration, Configurable $fallback) {
		parent::__construct($configuration, $fallback);
	}

	/**
	 * @param string $key
	 * @param $value
	 */
	public function set(string $key, $value) {
		$this->configuration->set($key, $value);
	}
}