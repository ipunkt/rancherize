<?php namespace Rancherize\Plugin;

/**
 * Interface Plugin
 */
interface Plugin {

	/**
	 * @return string
	 */
	function getName();

	/**
	 * @return string
	 */
	function getClasspath();
}