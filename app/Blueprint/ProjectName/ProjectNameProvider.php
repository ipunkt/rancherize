<?php namespace Rancherize\Blueprint\ProjectName;

use Rancherize\Blueprint\ProjectName\ProjectNameService\ProjectNameService;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class ProjectNameProvider
 * @package Rancherize\Blueprint\ProjectName
 */
class ProjectNameProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['project-name-service'] = function() {
			return new ProjectNameService;
		};
	}

	/**
	 */
	public function boot() {
	}
}