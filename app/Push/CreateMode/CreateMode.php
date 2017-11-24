<?php namespace Rancherize\Push\CreateMode;

use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\RancherService;

/**
 * Interface CreateMode
 * @package Rancherize\Push\CreateMode
 */
interface CreateMode {

	/**
	 * @param Configuration $configuration
	 * @param string $stackName
	 * @param string $serviceName
	 * @param string $version
	 * @param RancherService $rancherService
	 * @return
	 */
	function create(Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService);
}