<?php namespace Rancherize\RancherAccess\Exceptions;

/**
 * Class AccountNotFoundException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * Thrown when a rancher account is requested which is not present in the configuration
 */
class AccountNotFoundInConfigurationException extends AccountNotFoundException{
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
		parent::__construct("Account not found in configuration: $name. Please added it using the rancher:access command.", $code, $e);
		$this->name = $name;
	}
}