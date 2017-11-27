<?php namespace Rancherize\Push\CreateModeFactory;

use Rancherize\Configuration\Configuration;

/**
 * Class CreateModeParser
 * @package Rancherize\Push\CreateModeFactory
 */
class CreateModeParser {

	/**
	 * @param Configuration $configuration
	 * @param null $defaultMode
	 * @return string
	 */
	public function getCreateMode( Configuration $configuration, $defaultMode = null ) {
		return $configuration->get('rancher.create-mode', $defaultMode);
	}

}