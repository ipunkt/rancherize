<?php namespace Rancherize\Plugin\Exceptions;

use Rancherize\Exceptions\Exception;

/**
 * Class InstallFailedException
 * @package Rancherize\Plugin\Installer
 */
class InstallFailedException extends Exception {

	/**
	 * InstallFailedException constructor.
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(int $code = 0, \Exception $e = null) {
		parent::__construct("Plugin installation failed", $code, $e);
	}
}