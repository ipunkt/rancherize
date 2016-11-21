<?php namespace Rancherize\Configuration;

/**
 * Interface Configuration
 * @package Rancherize\Configuration
 */
interface Configuration {

	/**
	 * @param string $key
	 * @return bool
	 */
	function has(string $key) : bool;

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	function get(string $key, $default = null);
}