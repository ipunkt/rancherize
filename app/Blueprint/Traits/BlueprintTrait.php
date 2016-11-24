<?php


namespace Rancherize\Blueprint\Traits;


use Rancherize\Services\BlueprintService;

/**
 * Class BlueprintTrait
 * @package Rancherize\Blueprint\Traits
 *
 * Typehinted access to the BlueprintService in the container
 */
trait BlueprintTrait {

	/**
	 * @return BlueprintService
	 */
	private function getBlueprintService() : BlueprintService {
		return container('blueprint-service');
	}

}