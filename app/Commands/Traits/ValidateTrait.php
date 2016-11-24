<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\ValidateService;

trait ValidateTrait {
	/**
	 * @return ValidateService
	 */
	public function getValidateService() {
		return container('validate-service');
	}
}