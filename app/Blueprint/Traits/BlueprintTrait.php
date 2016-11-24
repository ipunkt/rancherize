<?php


namespace Rancherize\Blueprint\Traits;


use Rancherize\Services\BlueprintService;

trait BlueprintTrait {

	/**
	 * @return BlueprintService
	 */
	private function getBlueprintService() {
		return container('blueprint-service');
	}

}