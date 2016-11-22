<?php namespace Rancherize\Configuration\Traits;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Services\ConfigWrapper;

/**
 * Trait LoadsConfigurationTrait
 * @package Rancherize\Configuration\Traits
 */
trait LoadsConfigurationTrait {

	/**
	 * @return Configurable
	 */
	private function loadConfiguration() {
		/**
		 * @var ConfigWrapper $configWrapper
		 */
		$configWrapper = container('config-wrapper');
		$config = $configWrapper->configuration();

		$configWrapper->loadGlobalConfig($config);
		$configWrapper->loadProjectConfig($config);


		return $config;
	}
}