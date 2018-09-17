<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

use Rancherize\Blueprint\Infrastructure\Service\Volume;

/**
 * Class ObjectParser
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
class ObjectParser implements VolumeParser {

	/**
	 * @param $name
	 * @param $data
	 * @return Volume
	 */
	public function parse( $name, $data ) {
		$volume = new Volume;

		$volume->setExternalPath($name);
		if( array_key_exists('name', $data) )
			$volume->setExternalPath($data['name']);

		if( !array_key_exists('path', $data) )
			throw new VolumeParseException('Missing `path` for object style volume `'.$name.'`');

		$volume->setInternalPath($data['path']);

		$volume->setDriver('local');
		if( array_key_exists('driver', $data) )
			$volume->setDriver($data['driver']);

		if( array_key_exists('mount-options', $data) )
			$volume->setMountOptions($data['mount-options']);

		if( array_key_exists('driver-options', $data) )
		$volume->setOptions($data['driver-options']);

		return $volume;
	}
}