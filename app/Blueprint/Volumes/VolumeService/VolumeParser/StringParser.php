<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

use Rancherize\Blueprint\Infrastructure\Service\Volume;

/**
 * Class StringParser
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
class StringParser implements VolumeParser {

	/**
	 * @param $name
	 * @param $data
	 * @return Volume
	 */
	public function parse( $name, $data ) {
		$volume = new Volume();

		$volume->setExternalPath($name);
		$volume->setInternalPath($data);
		$volume->setDriver('local');

		return $volume;
	}
}