<?php


namespace Rancherize\Commands\Traits;


use Rancherize\Services\ValidateService;

/**
 * Class ValidateTrait
 * @package Rancherize\Commands\Traits
 *
 * Typehinted access to the ValidateService in the container
 */
trait ValidateTrait {
	/**
	 * @return ValidateService
	 */
	public function getValidateService() : ValidateService {
		return container('validate-service');
	}
}