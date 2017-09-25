<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class EnvironmentConfigurationService
 * @package Rancherize\Configuration\Services
 */
class EnvironmentConfigurationService {

	/**
	 * @param Configuration $configuration
	 * @param $environment
	 * @return Configuration
	 */
	public function environmentConfig(Configuration $configuration, $environment) : Configuration {
		$projectConfiguration = new PrefixConfigurationDecorator($configuration, 'project.default.');
		$environmentConfiguration = new PrefixConfigurationDecorator($configuration, "project.environments.$environment.");
		$config = new ConfigurationFallback($environmentConfiguration, $projectConfiguration);

		return $config;
	}

}