<?php namespace Rancherize\Services;
use Rancherize\Configuration\Configuration;

/**
 * Class EnvironmentService
 * @package Rancherize\Services
 */
class EnvironmentService {

	/**
	 * @param Configuration $configuration
	 * @return string[]
	 */
	public function allAvailable(Configuration $configuration) : array {

		$environments = $configuration->get('project.environments');

		return array_keys($environments);
	}
}