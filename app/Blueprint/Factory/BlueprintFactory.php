<?php namespace Rancherize\Blueprint\Factory;
use Closure;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Exceptions\BlueprintNotFoundException;

/**
 * Interface BlueprintFactory
 * @package Rancherize\Blueprint\Factory
 *
 * Manages known blueprints
 */
interface BlueprintFactory {

	/**
	 * Add the blueprint under classpath as name
	 *
	 * @param string $name
	 * @param string|Closure $classpathOrClosure
	 */
	function add( string $name, $classpathOrClosure);

	/**
	 * Return the blueprint registered under the given name
	 *
	 * @param string $name
	 * @return Blueprint
	 * @throws BlueprintNotFoundException
	 */
	function get(string $name) : Blueprint;


	/**
	 * Returns the names of all known blueprints
	 *
	 * @return string[]
	 */
	function available() : array;
}