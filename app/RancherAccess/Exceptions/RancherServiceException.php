<?php namespace Rancherize\RancherAccess\Exceptions;

use Rancherize\Exceptions\Exception;

/**
 * Class RancherServiceException
 * @package Rancherize\RancherAccess\Exceptions
 */
class RancherServiceException extends Exception {

	public function __construct($message = "", int $code = 1, \Throwable $exception = null) {
		parent::__construct($message, $code, $exception);
	}

}