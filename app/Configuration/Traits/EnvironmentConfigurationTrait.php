<?php namespace Rancherize\Configuration\Traits;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurationFallback;

/**
 * Trait EnvironmentConfigurationTrait
 * @package Rancherize\Configuration\Traits
 */
Trait EnvironmentConfigurationTrait {

	protected function environmentConfig(Configuration $configuration, $environment) {
		$projectConfiguration = new PrefixConfigurationDecorator($configuration, 'project.');
		$environmentConfiguration = new PrefixConfigurationDecorator($configuration, "project.$environment");
		$config = new ConfigurationFallback($environmentConfiguration, $projectConfiguration);

		return $config;
	}

}