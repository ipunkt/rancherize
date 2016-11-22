<?php


namespace Rancherize\Blueprint\Traits;


use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Symfony\Component\Console\Input\InputInterface;

trait LoadsBlueprintTrait {

	/**
	 * @return BlueprintFactory
	 */
	private function getBlueprintFactory() {
		return container('blueprint-factory');
	}

	/**
	 * @param InputInterface $input
	 * @param $blueprintName
	 * @return Blueprint
	 */
	protected function loadBlueprint(InputInterface $input, $blueprintName):Blueprint {
		$blueprintFactory = $this->getBlueprintFactory();
		$blueprint = $blueprintFactory->get($blueprintName);

		foreach ($input->getOptions() as $name => $value)
			$blueprint->setFlag($name, $value);
		return $blueprint;
	}

}