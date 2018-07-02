<?php namespace Rancherize\Configuration;

/**
 * Interface HasConfigVersion
 * @package Rancherize\Configuration
 */
interface HasSettableVersion {

	/**
	 * @param int $version
	 * @return mixed
	 */
	function setVersion( int $version);

}