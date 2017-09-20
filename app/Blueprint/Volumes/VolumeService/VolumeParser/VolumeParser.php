<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

use Rancherize\Blueprint\Infrastructure\Service\Volume;

/**
 * Interface VolumeParser
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
interface VolumeParser {

	/**
	 * @param $name
	 * @param $data
	 * @return Volume
	 */
	function parse($name, $data);

}