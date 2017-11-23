<?php namespace Rancherize\Push\Modes\InServiceUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\ModeFactory\PushModeParser;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;

/**
 * Class InServiceModeParser
 * @package Rancherize\Push\Modes\InServiceUpgrade
 */
class InServiceModeParser implements PushModeParser {
	/**
	 * @var InServiceChecker
	 */
	private $inServiceChecker;

	/**
	 * InServiceModeParser constructor.
	 * @param InServiceChecker $inServiceChecker
	 */
	public function __construct( InServiceChecker $inServiceChecker) {
		$this->inServiceChecker = $inServiceChecker;
	}

	/**
	 * Returns true if the associate PushMode was selected in the configuration
	 *
	 * @param Configuration $configuration
	 * @return bool
	 */
	public function isMode( Configuration $configuration ) {
		return $this->inServiceChecker->isInService($configuration);
	}
}