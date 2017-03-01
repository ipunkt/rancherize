<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

/**
 * Class VolumeNameSplitter
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class VolumeNameSplitter {

	/**
	 * @param $volumeKey
	 * @param $volumeName
	 * @return string[]
	 */
	public function split($volumeKey, $volumeName) {
		$nameAndVolume = explode(':', $volumeName);

		if( count($nameAndVolume) < 2 )
			return ['', $nameAndVolume[0] ];

		return $nameAndVolume;
	}
}