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
	 */
	function getProjectName( Configuration $configuration );
}