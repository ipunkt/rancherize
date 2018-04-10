<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;

/**
 * Class UpdatesBackendEnvironmentTrait
 * @deprecated  use SharedNetworkMode with the mainService and BACKEND_HOST=127.0.0.1:9000 instead*
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
		$this->updateBackendEnvironment = $enabled;
	}

}