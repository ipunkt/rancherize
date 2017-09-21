<?php namespace Rancherize\RancherAccess\NameMatcher;

/**
 * Interface NameMatcher
 * @package Rancherize\RancherAccess\NameMatcher
 */
interface NameMatcher {

	/**
	 * @param string $name
	 * @return bool
	 */
	function match( string $name );

	/**
	 * Return the name that is searched for to tell the user in an exception case
	 *
	 * @return string
	 */
	function getName();

}