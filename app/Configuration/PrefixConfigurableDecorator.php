<?php namespace Rancherize\Configuration;

/**
 * Class PrefixConfigurableDecorator
 * @package Rancherize\Configuration
 *
 * Entends the PrefixConfigrationDecorator to be appliable to Configrables
 */
class PrefixConfigurableDecorator extends PrefixConfigurationDecorator implements Configurable {
	/**
	 * @var Configurable
	 */
	protected $configurable;

	/**
	 * PrefixConfigurableDecorator constructor.
	 * @param Configurable $configurable
	 * @param string $prefix
	 */
	public function __construct(Configurable $configurable, string $prefix = null) {
		parent::__construct($configurable, $prefix);

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
}