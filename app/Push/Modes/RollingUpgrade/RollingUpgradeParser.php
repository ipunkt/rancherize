<?php namespace Rancherize\Push\Modes\RollingUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\ModeFactory\PushModeParser;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;

/**
 * Class RollingUpgradeParser
 * @package Rancherize\Push\Modes\RollingUpgrade
 *
 * Rolling Upgrade Parser - decides when to use the RollingPushMode
 * Currently when neither the InServeChecker nor the ReplaceUpgradeChecker claim the environment
 */
class RollingUpgradeParser implements PushModeParser {
	/**
	 * @var InServiceChecker
	 */
	private $inServiceChecker;
	/**
	 * @var ReplaceUpgradeChecker
	 */
	private $replaceUpgradeChecker;

	/**
	 * RollingUpgradeParser constructor.
	 * @param InServiceChecker $inServiceChecker
	 * @param ReplaceUpgradeChecker $replaceUpgradeChecker
	 */
	public function __construct( InServiceChecker $inServiceChecker, ReplaceUpgradeChecker $replaceUpgradeChecker) {
		$this->inServiceChecker = $inServiceChecker;
		$this->replaceUpgradeChecker = $replaceUpgradeChecker;
	}

	/**
	 * Returns true if the associate PushMode was selected in the configuration
	 *
	 * @param Configuration $configuration
	 * @return bool
	 */
	public function isMode( Configuration $configuration ) {
		if( $this->inServiceChecker->isInService($configuration) )
			return false;

		if( $this->replaceUpgradeChecker->isReplaceUpgrade($configuration) )
			return false;

		return true;
	}
}