<?php namespace Rancherize\Configuration;

/**
 * Class PrefixConfigurableDecorator
 * @package Rancherize\Configuration
 */
class PrefixConfigurableDecorator implements Configurable {
	/**
	 * @var Configurable
	 */
	protected $configurable;

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * PrefixConfigurableDecorator constructor.
	 * @param Configurable $configurable
	 * @param string $prefix
	 */
	public function __construct(Configurable $configurable, string $prefix = null) {
		if($prefix === null)
			$prefix = null;

		$this->configurable = $configurable;
		$this->prefix = $prefix;
	}

	/**
	 * @param string $key
	 * @param $value
	 */
	public function set(string $key, $value) {
		$prefixedKey = $this->prefix.$key;
		return $this->configurable->set($prefixedKey, $value);
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key) : bool {
		$prefixedKey = $this->prefix.$key;
		$this->configurable->has($prefixedKey);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key = null, $default = null) {
		$prefixedKey = $this->prefix.$key;
		$this->configurable->get($prefixedKey, $default);
	}
}