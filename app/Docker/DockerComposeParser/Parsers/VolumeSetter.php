<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

/**
 * Class VolumeSetter
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class VolumeSetter {


	/**
	 * @param array $data
	 * @param $volumes
	 */
	public function set(array &$data, $volumes) {
		$data['volumes'] = $volumes;
	}
}