<?php namespace Rancherize\Blueprint\Factory;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Exceptions\BlueprintNotFoundException;

/**
 * Interface BlueprintFactory
 * @package Rancherize\Blueprint\Factory
 */
interface BlueprintFactory {

	/**
	 * @param string $name
	 * @param string $classpath
	 */
	function add(string $name, string $classpath);

	/**
	 * @param string $name
	 * @return Blueprint
	 * @throws BlueprintNotFoundException
	 */
	function get(string $name) : Blueprint;


	/**
	 * @return string[]
	 */
	function available() : array;
}