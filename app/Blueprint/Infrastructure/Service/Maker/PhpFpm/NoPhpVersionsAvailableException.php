<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Rancherize\Exceptions\Exception;

/**
 * Class NoPhpVersionsAvailableException
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpmMaker
 */
class NoPhpVersionsAvailableException extends Exception {

	/**
	 * NoPhpVersionsAvailableException constructor.
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($code = 0, \Exception $e = null) {
		parent::__construct("A PhpFpm Service was requested but no versions are available.", $code, $e);
	}
}