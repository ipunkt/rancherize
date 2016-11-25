<?php namespace Rancherize\Blueprint\Validation\Rules;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Blueprint\Validation\Rule;
use Rancherize\Configuration\Configuration;

/**
 * Class RequiredRule
 * @package Rancherize\Blueprint\Validation\Rules
 */
class RequiredRule implements Rule {
	/**
	 * @var string
	 */
	private $fieldName;

	/**
	 * RequiredRule constructor.
	 * @param string $fieldName
	 */
	public function __construct(string $fieldName) {
		$this->fieldName = $fieldName;
	}

	/**
	 * @param Configuration $configuration
	 * @return mixed
	 */
	public function validate(Configuration $configuration) {
		if( ! $configuration->has($this->fieldName) )
			throw new ValidationFailedException([$this->fieldName => "Missing."]);
	}
}