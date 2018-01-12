<?php namespace Rancherize\Blueprint\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Network\Network;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Volume\Volume;

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
	 * @var Volume[]
	 */
	protected $volumes = [];

	/**
	 * @var Network[]
	 */
	protected $networks = [];

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
	 * @return Service[]
	 */
	public function getServices() : array {
		return $this->services;
	}

	/**
	 * @param Volume $volume
	 */
	public function addVolume(Volume $volume) {
		$this->volumes[$volume->getName()] = $volume;
	}

	/**
	 * @return Volume[]
	 */
	public function getVolumes(): array {
		return $this->volumes;
	}

	/**
	 * @return bool
	 */
	public function hasDockerfile() {
		return ($this->dockerfile !== null);
	}

	public function addNetwork(Network $network) {
		$this->networks[] = $network;
	}

	/**
	 * @return Network[]
	 */
	public function getNetworks(): array {
		return $this->networks;
	}
}