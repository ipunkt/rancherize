<?php namespace Rancherize\Blueprint\Validation\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class ValidationFailedException
 * @package Rancherize\Blueprint\Validation\Exceptions
 */
class ValidationFailedException extends Exception  {
	/**
	 * @var array
	 */
	private $failures;

	/**
	 * ValidationFailedException constructor.
	 * @param array $failures
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct(array $failures, $code = 0, \Exception $e = null) {
		parent::__construct("Validation failed", $code, $e);
		$this->failures = $failures;
	}

	/**
	 * @return array
	 */
	public function getFailures(): array {
		return $this->failures;
	}

}