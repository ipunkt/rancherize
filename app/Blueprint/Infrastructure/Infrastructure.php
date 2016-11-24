<?php namespace Rancherize\Blueprint\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class Infrastructure
 * @package Rancherize\Blueprint\Infrastructure
 *
 * An infrastructure built by a blueprint.
 * Consist of a single Dockerfile and multiple Services.
 */
class Infrastructure {

	/**
	 * @var Dockerfile
	 */
	protected $dockerfile = null;

	/**
	 * Service[]
	 */
	protected $services = [];

	/**
	 * @param Dockerfile $dockerfile
	 */
	public function setDockerfile(Dockerfile $dockerfile) {
		$this->dockerfile = $dockerfile;
	}

	/**
	 * @return Dockerfile
	 */
	public function getDockerfile(): Dockerfile {
		return $this->dockerfile;
	}

	/**
	 * @param Service $service
	 */
	public function addService(Service $service) {
		$this->services[] = $service;
	}

	/**
	 * @return mixed
	 */
	public function getServices() {
		return $this->services;
	}
}