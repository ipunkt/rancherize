<?php namespace Rancherize\Blueprint\Validation\RuleFactory;

use Rancherize\Blueprint\Validation\Rule;

/**
 * Interface RuleFactory
 * @package Rancherize\Blueprint\Validation\RuleFactory
 */
interface RuleFactory {
	/**
	 * @param $name
	 * @param string $fieldName
	 * @param array $parameters
	 * @return Rule
	 */
	function make($name, string $fieldName, array $parameters) : Rule;
}