<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;

/**
 * Class UpdatesBackendEnvironmentTrait
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits
 */
trait UpdatesBackendEnvironmentTrait {

	/**
	 * @var bool
	 */
	protected $updateBackendEnvironment = false;

	/**
	 * @param bool $enabled
	 */
	public function enableUpdateEnvironment($enabled = true) {
		var_dump( $enabled );

		die();

		$this->updateBackendEnvironment = $enabled;
	}

}