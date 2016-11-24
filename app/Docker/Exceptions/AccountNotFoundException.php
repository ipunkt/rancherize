<?php namespace Rancherize\Docker\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class AccountNotFoundException
 * @package Rancherize\Docker\Exceptions
 *
 * Thrown when a docker account is requested that is not present in the configuration
 */
class AccountNotFoundException extends Exception  {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * AccountNotFoundException constructor.
	 * @param string $name
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct($name, int $code = 0, \Exception $e = null) {
		parent::__construct("Docker account not found: $name", $code, $e);
		$this->name = $name;
	}

}