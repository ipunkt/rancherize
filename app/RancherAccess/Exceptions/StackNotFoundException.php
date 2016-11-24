<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class StackNotFoundException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * Thrown when RancherService::getStackidByName does not find a stack with the given name
 */
class StackNotFoundException extends Exception  {
	/**
	 * @var string
	 */
	private $stackName;

	/**
	 * StackNotFoundException constructor.
	 * @param string $serviceName
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $serviceName, int $code = 0, \Exception $e = null) {
		$this->stackName = $serviceName;
		parent::__construct("Stack not found $serviceName", $code, $e);
	}
}