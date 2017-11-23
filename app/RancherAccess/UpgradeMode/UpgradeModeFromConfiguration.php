<?php namespace Rancherize\RancherAccess\UpgradeMode;

use Rancherize\Configuration\Configuration;

/**
 * Class UpgradeModeFromConfiguration
 * @package Rancherize\RancherAccess\UpgradeMode
 */
class UpgradeModeFromConfiguration {

	/**
	 * @param Configuration $configuration
	 * @param $default
	 * @return mixed
	 */
	public function getUpgradeMode( Configuration $configuration, $default = null ) {
		return $configuration->get('rancher.upgrade-mode', $default );
	}

}