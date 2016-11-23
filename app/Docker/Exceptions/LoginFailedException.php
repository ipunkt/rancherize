<?php namespace Rancherize\Docker\Exceptions;

/**
 * Class LoginFailedException
 * @package Rancherize\Docker\Exceptions
 */
class LoginFailedException extends DockerException  {
	public function __construct(string $message = '', int $code = 0, \Exception $e = null) {
		parent::__construct("Docker login failed: $message", $code, $e);
	}
}