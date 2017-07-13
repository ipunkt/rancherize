<?php namespace Rancherize\Docker;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DockerProvider
 * @package Rancherize\Docker
 */
class DockerProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[''] = function($c) {
			return new DockerAccessService($c['event']);
		};
	}

	/**
	 */
	public function boot() {
	}
}