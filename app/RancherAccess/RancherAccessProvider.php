<?php namespace Rancherize\RancherAccess;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DockerProvider
 * @package Rancherize\Docker
 */
class RancherAccessProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['rancher-access-service'] = function($c) {
			return new RancherAccessConfigService();
		};
	}

	/**
	 */
	public function boot() {
	}
}