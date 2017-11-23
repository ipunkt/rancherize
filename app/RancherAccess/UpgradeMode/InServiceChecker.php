<?php namespace Rancherize\RancherAccess\UpgradeMode;

use Rancherize\Configuration\Configuration;

/**
 * Class InServiceChecker
 * @package Rancherize\RancherAccess
 */
class InServiceChecker {
	/**
	 * @var UpgradeModeFromConfiguration
	 */
	private $upgradeModeFromConfiguration;

	/**
	 * InServiceChecker constructor.
	 * @param UpgradeModeFromConfiguration $upgradeModeFromConfiguration
	 */
	public function __construct( UpgradeModeFromConfiguration $upgradeModeFromConfiguration) {
		$this->upgradeModeFromConfiguration = $upgradeModeFromConfiguration;
	}

	/**
	 * @param Configuration $config
	 * @return bool
	 */
	public function isInService(Configuration $config) {

		$inServiceValue = $config->get('rancher.in-service', false);

		$upgradeModeFromInServiceValue = null;
		if( $inServiceValue )
			$upgradeModeFromInServiceValue = 'in-service';

		$upgradeModeValue = $this->upgradeModeFromConfiguration->getUpgradeMode($config, $upgradeModeFromInServiceValue );


		if( $upgradeModeValue === 'in-service' )
			return true;

		return false;
	}

}