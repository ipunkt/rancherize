<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations;

/**
 * Interface UpdatesBackendEnvironment
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations
 */
interface UpdatesBackendEnvironment {

	/**
	 * @param bool $enabled
	 */
	function enableUpdateEnvironment($enabled = true);

}