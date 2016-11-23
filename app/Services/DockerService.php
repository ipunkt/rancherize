<?php namespace Rancherize\Services;

/**
 * Class DockerService
 * @package Rancherize\Services
 */
class DockerService {

	/**
	 * @param string $imageName
	 * @param string $dockerfile
	 */
	public function build(string $imageName, $dockerfile = null) {

		if( $dockerfile === null )
			$dockerfile = 'Dockerfile';

		$success = 0;

		passthru("docker build -f $dockerfile -t $imageName .", $success);
	}

	/**
	 * @param string $imageName
	 * @param string $dockerfile
	 */
	public function push(string $imageName) {

		$success = 0;

		passthru("docker push $imageName", $success);
	}

}