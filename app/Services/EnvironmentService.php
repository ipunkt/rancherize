<?php namespace Rancherize\Services;
use Rancherize\Configuration\Configuration;

/**
 * Class EnvironmentService
 * @package Rancherize\Services
 *
 * Access to the environments of the configuration
 */
class EnvironmentService {

	/**
	 * Returns the names of all available environments
	 *
	 * @param Configuration $configuration
	 * @return string[]
	 */
	public function allAvailable(Configuration $configuration) : array {

		$environments = $configuration->get('project.environments');

		return array_keys($environments);
	}
}