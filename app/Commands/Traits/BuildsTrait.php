<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\BuildService;

trait BuildsTrait {

	/**
	 * @return BuildService
	 */
	public function getBuildService() : BuildService {
		return container('build-service');
	}
}