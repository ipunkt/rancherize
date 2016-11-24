<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Configuration;

/**
 * Class BlueprintService
 * @package Rancherize\Services
 *
 * Load the blueprint from the known blueprints
 */
class BlueprintService {
	/**
	 * @var BlueprintFactory
	 */
	private $blueprintFactory;

	/**
	 * BlueprintService constructor.
	 * @param BlueprintFactory $blueprintFactory
	 */
	public function __construct(BlueprintFactory $blueprintFactory) {
		$this->blueprintFactory = $blueprintFactory;
	}

	/**
	 * Retrieve the blueprint that was set in the configuration
	 *
	 * @param Configuration $configuration
	 * @param array $flags
	 * @return \Rancherize\Blueprint\Blueprint
	 */
	public function byConfiguration(Configuration $configuration, array $flags) : Blueprint {
		$blueprintName = $configuration->get('project.blueprint');

		return $this->load($blueprintName, $flags);
	}

	/**
	 * Load by name
	 *
	 * @param string $blueprintName
	 * @param array $flags
	 * @return \Rancherize\Blueprint\Blueprint
	 * @internal param array $options
	 */
	public function load(string $blueprintName, array $flags) : Blueprint {
		$blueprint = $this->blueprintFactory->get($blueprintName);

		foreach($flags as $name => $value)
			$blueprint->setFlag($name, $value);

		return $blueprint;
	}

}