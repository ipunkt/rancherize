<?php namespace Rancherize\Push\Modes;

use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\RancherService;

/**
 * Interface PushMode
 * @package Rancherize\Push\Modes
 */
interface PushMode {

	/**
	 * @param Configuration $configuration
	 * @param $stackName
	 * @param $serviceName
	 * @param $version
	 * @param RancherService $rancherService
	 */
	function push(Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService);
}