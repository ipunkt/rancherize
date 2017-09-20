<?php namespace Rancherize\Blueprint\ProjectName\ProjectNameService;

use Rancherize\Configuration\Configuration;

/**
 * Class ProjectNameService
 * @package Rancherize\Blueprint\ProjectName\ProjectNameService
 *
 * Find the project name for initializing the configuration
 */
interface ProjectNameService {

	/**
	 * @param Configuration $configuration
	 * @param string $default Defaults to ''
	 * @return
	 */
	function getProjectName( Configuration $configuration, $default = null );
}