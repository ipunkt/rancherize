<?php namespace Rancherize\Configuration;

/**
 * Interface Configurable
 * @package Rancherize\Configuration
 *
 * Extends Configuration by the option to set values
 */
interface Configurable extends Configuration {
	/**
	 * @param string $key
	 * @param $value
	 */
	function set(string $key, $value);
}