<?php namespace Rancherize\Blueprint\Validation\Exceptions;

use Rancherize\Exceptions\Exception;

/**
 * Class InvalidRuleException
 * @package Rancherize\Blueprint\Validation\Exceptions
 *
 * This exception is thrown when the validator encounters a rule that is not defined
 */
class InvalidRuleException extends Exception {

	/**
	 * InvalidRuleException constructor.
	 * @param string $rule
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct(string $rule, int $code = 91, \Exception $e = null) {
		parent::__construct("An invalid rule was requested from the validator: $rule", $code, $e);
	}

}