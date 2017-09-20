<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

/**
 * Interface ParsesVolume
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
interface ParsesVolume {

	/**
	 * @param string|int $name
	 * @param mixed $data
	 * @return bool
	 */
	function parsesVolume($name, $data);

}