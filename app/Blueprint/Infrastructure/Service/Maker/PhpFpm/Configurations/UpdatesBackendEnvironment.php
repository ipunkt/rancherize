<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations;

/**
 * Interface UpdatesBackendEnvironment
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations
 *
 * @deprecated use SharedNetworkMode with the mainService and BACKEND_HOST=127.0.0.1:9000 instead
 */
interface UpdatesBackendEnvironment {

	/**
	 * @param bool $enabled
	 */
	function enableUpdateEnvironment($enabled = true);

}