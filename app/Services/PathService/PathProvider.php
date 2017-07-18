<?php namespace Rancherize\Services\PathService;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class PathProvider
 * @package Rancherize\Services\PathService
 */
class PathProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['path-service'] = function($c) {
			return new PathService();
		};
	}

	/**
	 */
	public function boot() {
	}
}