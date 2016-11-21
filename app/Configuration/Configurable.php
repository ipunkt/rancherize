<?php namespace Rancherize\Configuration;

/**
 * Interface Configurable
 * @package Rancherize\Configuration
 */
interface Configurable extends Configuration {
	/**
	 * @param string $key
	 * @param $value
	 */
	function set(string $key, $value);
}