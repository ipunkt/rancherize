<?php namespace Rancherize\RancherAccess\UpgradeMode;

use Rancherize\Configuration\Configuration;

/**
 * Class ReplaceUpgradeChecker
 * @package Rancherize\RancherAccess\UpgradeMode
 */
class ReplaceUpgradeChecker {
	/**
	 * @var UpgradeModeFromConfiguration
	 */
	private $upgradeModeFromConfiguration;

	/**
	 * ReplaceUpgradeChecker constructor.
	 * @param UpgradeModeFromConfiguration $upgradeModeFromConfiguration
	 */
	public function __construct( UpgradeModeFromConfiguration $upgradeModeFromConfiguration) {
		$this->upgradeModeFromConfiguration = $upgradeModeFromConfiguration;
	}

	/**
	 * @param Configuration $configuration
	 * @return bool
	 */
	public function isReplaceUpgrade( Configuration $configuration ) {

		if( $this->upgradeModeFromConfiguration->getUpgradeMode($configuration) === 'replace')
			return true;

		return false;

	}

}