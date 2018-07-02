<?php namespace Rancherize\Configuration;

/**
 * Class PrefixConfigurableDecorator
 * @package Rancherize\Configuration
 *
 * This decorator prepends all requests to the Configration with a given prefix
 */
class PrefixConfigurationDecorator implements Configuration {
	/**
	 * @var Configuration
	 */
	protected $configurable;

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * PrefixConfigurableDecorator constructor.
	 * @param Configurable $configuration
	 * @param string $prefix
	 */
	public function __construct(Configuration $configuration, string $prefix = null) {
		if($prefix === null)
			$prefix = null;

		$this->configurable = $configuration;
		$this->prefix = $prefix;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key) : bool {
		$prefixedKey = $this->prefix.$key;
		return $this->configurable->has($prefixedKey);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key = null, $default = null) {
		$prefixedKey = $this->prefix.$key;
		return $this->configurable->get($prefixedKey, $default);
	}

	/**
	 * @return int
	 */
	public function version(): int {
		return $this->configurable->version();
	}
}