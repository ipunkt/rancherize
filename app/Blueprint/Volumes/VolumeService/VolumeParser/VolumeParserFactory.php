<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

/**
 * Interface VolumeParserFactory
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
interface VolumeParserFactory {

	/**
	 * @param $type
	 * @return VolumeParser
	 */
	function getParser( $type );

}