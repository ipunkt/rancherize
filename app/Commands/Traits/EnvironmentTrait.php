<?php namespace Rancherize\Commands\Traits;
use Rancherize\Services\EnvironmentService;

/**
 * Class EnvironmentTrait
 * @package Rancherize\Commands\Traits
 */
trait EnvironmentTrait {

	/**
	 * @return EnvironmentService
	 */
	public function getEnvironmentService() : EnvironmentService {
		return container('environment-service');
	}
}