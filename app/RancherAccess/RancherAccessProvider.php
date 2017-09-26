<?php namespace Rancherize\RancherAccess;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\ApiService\ApiService;
use Rancherize\RancherAccess\ApiService\CurlApiService;

/**
 * Class DockerProvider
 * @package Rancherize\Docker
 */
class RancherAccessProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[ApiService::class] = function() {
			return new CurlApiService();
		};

		$this->container[RancherAccessService::class] = function() {
			return new RancherAccessConfigService();
		};

		$container[RancherService::class] = function($c) {
			return new RancherService( $c[ApiService::class] );
		};

	}

	/**
	 */
	public function boot() {
	}
}