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
	 * @var BlueprintService
	 */
	protected $blueprintService;

	/**
	 * @param BlueprintService $blueprintService
	 */
	public function setBlueprintService( BlueprintService $blueprintService ) {
		$this->blueprintService = $blueprintService;
	}

}