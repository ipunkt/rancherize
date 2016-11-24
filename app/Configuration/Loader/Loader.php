<?php namespace Rancherize\Configuration\Loader;
use Rancherize\Configuration\Configurable;

/**
 * Interface Loader
 * @package Rancherize\Configuration\Loader
 *
 * Load a configuration from file
 */
interface Loader {

	/**
	 * @param Configurable $configurable
	 * @param string $path
	 */
	function load(Configurable $configurable, string $path);

	/**
	 * @param string|null $prefix
	 * @return $this
	 */
	function setPrefix(string $prefix = null): Loader;

}