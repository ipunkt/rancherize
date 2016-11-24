<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\DockerService;

/**
 * Class DockerTrait
 * @package Rancherize\Commands\Traits
 *
 * Typehinted access to the DockerService in the container
 */
trait DockerTrait {

	/**
	 * @return DockerService
	 */
	public function getDocker() : DockerService {
		return container('docker-service');
	}

}