<?php namespace Rancherize\Configuration;

/**
 * Interface Configuration
 * @package Rancherize\Configuration
 *
 * Access configuration values based on variable names. Traverses arrays using the dot notationt:
 * a.b.c attempts to return the variable a['b']['c']
 */
interface Configuration {

	/**
	 * Returns true if the given key was found
	 *
	 * @param string $key
	 * @return bool
	 */
	function has(string $key) : bool;

	/**
	 * Returns the value set for the given key.
	 * If the key was not found the default is returned instead
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	function get(string $key = null, $default = null);
}