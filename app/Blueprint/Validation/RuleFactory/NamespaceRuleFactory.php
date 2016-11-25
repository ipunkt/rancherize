<?php namespace Rancherize\Blueprint\Validation\RuleFactory;
use Rancherize\Blueprint\Validation\Exceptions\InvalidRuleException;
use Rancherize\Blueprint\Validation\Rule;

/**
 * Class NamespaceRuleFactory
 * @package Rancherize\Blueprint\Validation\RuleFactory
 */
class NamespaceRuleFactory implements RuleFactory {
	/**
	 * @var string
	 */
	private $namespace;

	/**
	 * NamespaceRuleFactory constructor.
	 * @param string $namespace
	 */
	public function __construct(string $namespace) {
		$this->namespace = $namespace;
	}

	/**
	 * @param $name
	 * @param string $fieldName
	 * @param array $parameters
	 * @return Rule
	 */
	public function make($name, string $fieldName, array $parameters): Rule {
		$translatedName = '';

		$words = explode('-', $name);
		foreach($words as $word)
			$translatedName .= ucfirst(strtolower($word));

		$translatedName .= 'Rule';

		$classPath = '\\'.$this->namespace.'\\'.$translatedName;

		if( !class_exists($classPath) )
			throw new InvalidRuleException($name);

		return new $classPath($fieldName, $parameters);
	 }
}