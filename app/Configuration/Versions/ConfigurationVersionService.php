<?php namespace Rancherize\Configuration\Versions;

/**
 * Interface ConfigurationVersionService
 * @package Rancherize\Configuration\Versions
 *
 * Used by any configuration parser to find out which configuration version the file uses.
 * This is used to change default values to more sensible values or object notation that used to be string notation -
 * without breaking backwards compatibility and requiring a new major version
 */
interface ConfigurationVersionService {

	/**
	 * @param $version
	 * @return bool
	 */
	function isVersion($version);

	/**
	 * @param int $minVersion
	 * @param int $maxVersion
	 * @return bool
	 */
	function versionRange($minVersion, $maxVersion);
}