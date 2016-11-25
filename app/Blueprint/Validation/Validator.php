<?php namespace Rancherize\Blueprint\Validation;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Blueprint\Validation\RuleFactory\RuleFactory;
use Rancherize\Configuration\Configuration;

/**
 * Class Validator
 * @package Rancherize\Blueprint\Validation
 *
 * Requirement: BN01
 */
class Validator {
	/**
	 * @var RuleFactory
	 */
	private $ruleFactory;

	/**
	 * Validator constructor.
	 * @param RuleFactory $ruleFactory
	 */
	public function __construct(RuleFactory $ruleFactory) {
		$this->ruleFactory = $ruleFactory;
	}

	/**
	 * @param Configuration $configuration
	 * @param array $fieldList
	 */
	public function validate(Configuration $configuration, array $fieldList) {

		$errors = [];

		foreach($fieldList as $field => $rulelist) {

			$rules = explode('|', $rulelist);


			foreach($rules as $ruleText) {
				$nameAndParameters = explode(':', $ruleText);
				$ruleName = $nameAndParameters[0];

				$ruleParameterList = '';
				if( 1 < count($nameAndParameters) )
					$ruleParameterList = $nameAndParameters[1];

				$ruleParameters = explode(',', $ruleParameterList);

				try {

					$this->ruleFactory->make($ruleName, $field, $ruleParameters)->validate($configuration);

				} catch(ValidationFailedException $e) {

					if( !array_key_exists($field, $errors))
						$errors[$field] = [];

					$errors[$field] = array_merge( $errors[$field], $e->getFailures() );

				}

			}

		}

		if( empty($errors) )
			return;

		throw new ValidationFailedException($errors);
	}
}