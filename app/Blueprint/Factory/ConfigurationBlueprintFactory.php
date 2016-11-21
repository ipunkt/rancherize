<?php namespace Rancherize\Blueprint\Factory;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Exceptions\BlueprintNotFoundException;
use Rancherize\Configuration\Configuration;

/**
 * Class ConfigurationBlueprintFactory
 * @package Rancherize\Blueprint\Factory
 */
class ConfigurationBlueprintFactory implements BlueprintFactory  {
	/**
	 * @var Configuration
	 */
	private $configuration;

	/**
	 * ConfigurationBlueprintFactory constructor.
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * @param string $name
	 * @param string $classpath
	 */
	public function add(string $name, string $classpath) {
		// TODO: Implement add() method.
	}

	/**
	 * @param string $name
	 * @return Blueprint
	 * @throws BlueprintNotFoundException
	 */
	public function get(string $name) : Blueprint {
		$blueprints = $this->getBlueprints();
		if( !array_key_exists($name, $blueprints) )
			throw new BlueprintNotFoundException($name);

		$blueprintClasspath = $blueprints[$name];

		return new $blueprintClasspath;
	}

	/**
	 * @return array
	 */
	protected function getBlueprints() {
		return $this->configuration->get('blueprints');
	}
}