<?php namespace Rancherize\Blueprint\Factory;
use Pimple\Container;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Exceptions\BlueprintNotFoundException;

/**
 * Class ConfigurationBlueprintFactory
 * @package Rancherize\Blueprint\Factory
 *
 * This blueprint factory uses the rancherize.json to store the blueprints
 */
class ContainerBlueprintFactory implements BlueprintFactory  {

	private $blueprintNames = [

	];
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var string
	 */
	private $prefix = 'blueprint.';

	/**
	 * ConfigurationBlueprintFactory constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container) {
		$this->container = $container;
	}

	/**
	 * @param string $name
	 * @param string $classpath
	 */
	public function add(string $name, string $classpath) {
		$key = $this->buildKey($name);

		$this->container[$key] = function() use ($classpath) {
			return new $classpath;
		};

		$this->blueprintNames[$name] = $name;
	}

	/**
	 * @param string $name
	 * @return Blueprint
	 * @throws BlueprintNotFoundException
	 */
	public function get(string $name) : Blueprint {
		$key = $this->buildKey($name);

		if(array_key_exists($name, $this->blueprintNames) )
			throw new BlueprintNotFoundException($name);

		$blueprint = $this->container[$key];

		return $blueprint;
	}

	/**
	 * @return string[]
	 */
	public function available() : array {
		return $this->blueprintNames;
	}

	/**
	 * @param $blueprintName
	 */
	private function buildKey($blueprintName) {
		return $this->prefix.$blueprintName;
	}
}