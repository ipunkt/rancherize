<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Factory\BlueprintFactory;

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
	 * @param string $blueprintName
	 * @param array $options
	 */
	public function load(string $blueprintName, array $flags) {
		$blueprint = $this->blueprintFactory->get($blueprintName);

		foreach($flags as $name => $value)
			$blueprint->setFlag($name, $value);

		return $blueprint;
	}

}