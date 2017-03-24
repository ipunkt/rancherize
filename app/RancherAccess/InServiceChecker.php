<?php namespace Rancherize\RancherAccess;

use Rancherize\Configuration\Configuration;

/**
 * Class InServiceChecker
 * @package Rancherize\RancherAccess
 */
class InServiceChecker {

	/**
	 * @param Configuration $config
	 * @return bool
	 */
	public function isInService(Configuration $config) {

		if( !$config->get('rancher.in-service', false) )
			return false;

		return true;
	}

}