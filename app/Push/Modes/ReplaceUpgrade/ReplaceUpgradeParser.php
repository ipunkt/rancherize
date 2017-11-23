<?php namespace Rancherize\Push\Modes\ReplaceUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\ModeFactory\PushModeParser;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;

/**
 * Class ReplaceUpgradeParser
 * @package Rancherize\Push\Modes\ReplaceUpgrade
 */
class ReplaceUpgradeParser implements PushModeParser {
	/**
	 * @var ReplaceUpgradeChecker
	 */
	private $replaceUpgradeChecker;

	/**
	 * ReplaceUpgradeParser constructor.
	 * @param ReplaceUpgradeChecker $replaceUpgradeChecker
	 */
	public function __construct( ReplaceUpgradeChecker $replaceUpgradeChecker) {
		$this->replaceUpgradeChecker = $replaceUpgradeChecker;
	}

	/**
	 * Returns true if the associate PushMode was selected in the configuration
	 *
	 * @param Configuration $configuration
	 * @return bool
	 */
	public function isMode( Configuration $configuration ) {
		return $this->replaceUpgradeChecker->isReplaceUpgrade( $configuration );
	}
}