<?php namespace Rancherize\Docker\Exceptions;

/**
 * Class BuildFailedException
 * @package Rancherize\Docker\Exceptions
 *
 * Thrown when the docker build command for an image was not succesful
 */
class BuildFailedException extends DockerException {

	/**
	 * BuildFailedException constructor.
	 * @param string $imageName
	 * @param string $dockerfile
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $imageName, string $dockerfile, int $code = 0, \Exception $e = null) {
		parent::__construct("Docker build failed for $imageName with $dockerfile", $code, $e);
	}
}