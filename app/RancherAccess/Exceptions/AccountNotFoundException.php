<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class AccountNotFoundException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * Thrown when a rancher account is requested which is not present in the configuration
 */
class AccountNotFoundException extends Exception {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * AccountNotFoundException constructor.
	 * @param string $name
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($name, $code = 0, \Exception $e = null) {
		parent::__construct("Account not found: $name", $code, $e);
		$this->name = $name;
	}
}