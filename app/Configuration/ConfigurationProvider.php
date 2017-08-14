<?php namespace Rancherize\Configuration;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class ConfigurationProvider
 * @package Rancherize\Configuration
 */
class ConfigurationProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['config-array-adder'] = function() {
			return new ArrayConfiguration();
		};
	}

	/**
	 */
	public function boot() {
	}
}