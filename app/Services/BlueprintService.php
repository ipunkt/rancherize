<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Configuration;

/**
 * Class BlueprintService
 * @package Rancherize\Services
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
	 * @param Configuration $configuration
	 * @param array $flags
	 */
	public function byConfiguration(Configuration $configuration, array $flags) {
		$blueprintName = $configuration->get('project.blueprint');

		return $this->load($blueprintName, $flags);
	}

	/**
	 * @param string $blueprintName
	 * @param array $flags
	 * @return \Rancherize\Blueprint\Blueprint
	 * @internal param array $options
	 */
	public function load(string $blueprintName, array $flags) {
		$blueprint = $this->blueprintFactory->get($blueprintName);

		foreach($flags as $name => $value)
			$blueprint->setFlag($name, $value);

		return $blueprint;
	}

}