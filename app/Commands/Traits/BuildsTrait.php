<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\BuildService;

/**
 * Class BuildsTrait
 * @package Rancherize\Commands\Traits
 *
 * Typehinted access to the BuildService in the container
 */
trait BuildsTrait {

	/**
	 * @return BuildService
	 */
	public function getBuildService() : BuildService {
		return container('build-service');
	}
}