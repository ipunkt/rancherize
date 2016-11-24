<?php namespace Rancherize\Configuration\Traits;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurationFallback;

/**
 * Trait EnvironmentConfigurationTrait
 * @package Rancherize\Configuration\Traits
 *
 * Returns a configuration for the given environment which falls back to project.default.* if project.environments.ENVIRONMENT.*
 * is not found
 */
Trait EnvironmentConfigurationTrait {

	/**
	 * @param Configuration $configuration
	 * @param $environment
	 * @return Configuration
	 */
	protected function environmentConfig(Configuration $configuration, $environment) : Configuration {
		$projectConfiguration = new PrefixConfigurationDecorator($configuration, 'project.default.');
		$environmentConfiguration = new PrefixConfigurationDecorator($configuration, "project.environments.$environment.");
		$config = new ConfigurationFallback($environmentConfiguration, $projectConfiguration);

		return $config;
	}

}