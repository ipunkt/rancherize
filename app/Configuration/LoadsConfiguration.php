<?php namespace Rancherize\Configuration;

/**
 * Interface LoadsConfiguration
 * @package Rancherize\Configuration
 */
interface LoadsConfiguration {

	/**
	 * @param Configurable $configurable
	 */
	function setConfiguration(Configurable $configurable);

}