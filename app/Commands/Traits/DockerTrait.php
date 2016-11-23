<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\DockerService;

trait DockerTrait {

	/**
	 * @return DockerService
	 */
	public function getDocker() : DockerService {
		return container('docker-service');
	}

}