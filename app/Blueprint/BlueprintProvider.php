<?php namespace Rancherize\Blueprint;

use Rancherize\Blueprint\Factory\ContainerBlueprintFactory;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class BlueprintProvider
 * @package Rancherize\Blueprint
 */
class BlueprintProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['blueprint-factory'] = function($c) {
			return new ContainerBlueprintFactory($c);
		};
	}

	/**
	 */
	public function boot() {
	}
}