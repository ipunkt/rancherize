<?php namespace Rancherize\Configuration\Versions;

/**
 * Interface ConfigurationVersionService
 * @package Rancherize\Configuration\Versions
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