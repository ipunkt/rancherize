<?php namespace Rancherize\Blueprint\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;

/**
 * Class Infrastructure
 * @package Rancherize\Blueprint\Infrastructure
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
}