<?php namespace Rancherize\Push\ModeFactory;

use Rancherize\Configuration\Configuration;

/**
 * Interface PushModeParser
 * @package Rancherize\Push\PushModeFactory
 */
interface PushModeParser {

	/**
	 * Returns true if the associate PushMode was selected in the configuration
	 *
	 * @param Configuration $configuration
	 * @return bool
	 */
	function isMode(Configuration $configuration);
}