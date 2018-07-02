<?php namespace Rancherize\RancherAccess\UpgradeMode;

use Rancherize\Configuration\Configuration;

/**
 * Class RollingUpgradeChecker
 * @package Rancherize\RancherAccess\UpgradeMode
 */
class RollingUpgradeChecker {
	/**
	 * @var InServiceChecker
	 */
	private $inServiceChecker;
	/**
	 * @var ReplaceUpgradeChecker
	 */
	private $replaceUpgradeChecker;

	/**
	 * RollingUpgradeChecker constructor.
	 * @param InServiceChecker $inServiceChecker
	 * @param ReplaceUpgradeChecker $replaceUpgradeChecker
	 */
	public function __construct( InServiceChecker $inServiceChecker, ReplaceUpgradeChecker $replaceUpgradeChecker ) {
		$this->inServiceChecker = $inServiceChecker;
		$this->replaceUpgradeChecker = $replaceUpgradeChecker;
	}

	/**
	 * @param Configuration $configuration
	 * @return bool
	 */
	public function isRollingUpgrade( Configuration $configuration ) {
		if ( $this->inServiceChecker->isInService( $configuration ) )
			return false;

		if ( $this->replaceUpgradeChecker->isReplaceUpgrade( $configuration ) )
			return false;

		// New default in version to is in-service
		if ( $configuration->version() !== 1 )
			return false;

		return true;
	}
}