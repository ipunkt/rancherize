<?php


namespace Rancherize\Blueprint\Validation\Traits;


use Rancherize\Blueprint\Validation\Validator;

trait HasValidatorTrait {
	/**
	 * @var Validator
	 */
	protected $validator = null;

	/**
	 * @param Validator $validator
	 * @return $this
	 */
	public function setValidator(Validator $validator) {
		$this->validator = $validator;
		return $this;
	}

	/**
	 * @return Validator
	 */
	public function getValidator(): Validator {
		if($this->validator === null)
			$this->validator = container('blueprint-validator');

		return $this->validator;
	}
}