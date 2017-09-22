<?php namespace Rancherize\RancherAccess;

use Rancherize\Configuration\Configuration;

/**
 * Interface RancherAccessParsesConfiguration
 * @package Rancherize\RancherAccess
 */
interface RancherAccessParsesConfiguration {


	/**
	 * @param Configuration $config
	 */
	public function parse( Configuration $config);
}