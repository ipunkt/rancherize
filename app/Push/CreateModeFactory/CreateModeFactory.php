<?php namespace Rancherize\Push\CreateModeFactory;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\CreateMode\CreateMode;

/**
 * Interface CreateModeFactory
 * @package Rancherize\Push\CreateModeFactory
 */
interface CreateModeFactory {

	/**
	 * @param Configuration $configuration
	 * @return CreateMode
	 */
	function make(Configuration $configuration);

	/**
	 * @param $modeName
	 * @param CreateMode $createMode
	 */
	function register($modeName, CreateMode $createMode);

	/**
	 * Sets the default mode if the configuration does not have a setting
	 * Note that if this is not called the first mode registered will be used as default
	 *
	 * @param $defaultModeName
	 */
	function setDefaultMode(string $defaultModeName);
}