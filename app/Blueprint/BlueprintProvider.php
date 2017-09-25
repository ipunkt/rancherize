<?php namespace Rancherize\Blueprint;

use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Blueprint\Factory\ContainerBlueprintFactory;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Services\BlueprintService;

/**
 * Class BlueprintProvider
 * @package Rancherize\Blueprint
 */
class BlueprintProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[BlueprintFactory::class] = function($c) {
			return new ContainerBlueprintFactory($c);
		};

		$this->container[BlueprintService::class] = function($c) {
			return new BlueprintService($c[BlueprintFactory::class]);
		};
	}

	/**
	 */
	public function boot() {
	}
}