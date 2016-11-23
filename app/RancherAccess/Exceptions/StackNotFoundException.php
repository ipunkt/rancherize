<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class StackNotFoundException
 * @package Rancherize\RancherAccess\Exceptions
 */
class StackNotFoundException extends Exception  {
	/**
	 * @var string
	 */
	private $stackName;

	/**
	 * StackNotFoundException constructor.
	 * @param string $stackName
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $stackName, int $code = 0, \Exception $e = null) {
		$this->stackName = $stackName;
		parent::__construct("Stack not found $stackName", $code, $e);
	}
}