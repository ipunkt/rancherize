<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Rancherize\Exceptions\Exception;

/**
 * Class PhpVersionNotAvailableException
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpmMaker
 */
class PhpVersionNotAvailableException extends Exception {

	/**
	 * PhpVersionNotAvailableException constructor.
	 * @param mixed $phpVersion
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($phpVersion, $code = 0, \Exception $e = null) {
		parent::__construct("Requested PHP Version $phpVersion is not available.", $code, $e);
	}
}