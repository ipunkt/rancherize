<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;

/**
 * Class ConfigWrapper
 * @package Rancherize\Configuration\Services
 *
 * Easy use wrapper for GlobalConfiguration, ProjectConfiguration and EnvironmentConfiguration
 */
class ConfigWrapper {
	/**
	 * @var GlobalConfiguration
	 */
	private $globalConfiguration;
	/**
	 * @var ProjectConfiguration
	 */
	private $projectConfiguration;
	/**
	 * @var Configurable
	 */
	private $configurable;

	/**
	 * ConfigWrapper constructor.
	 * @param GlobalConfiguration $globalConfiguration
	 * @param ProjectConfiguration $projectConfiguration
	 * @param Configurable $configurable
	 */
	public function __construct(GlobalConfiguration $globalConfiguration, ProjectConfiguration $projectConfiguration, Configurable $configurable) {
		$this->globalConfiguration = $globalConfiguration;
		$this->projectConfiguration = $projectConfiguration;
		$this->configurable = $configurable;
	}

	/**
	 * @return GlobalConfiguration
	 */
	public function globalConfiguration() {
		return $this->globalConfiguration;
	}

	/**
	 * @return ProjectConfiguration
	 */
	public function projectConfiguration() {
		return $this->projectConfiguration;
	}

	/**
	 * @return Configurable
	 */
	public function configuration() {
		return $this->configurable;
	}

	/**
	 * @param Configurable $configurable
	 */
	public function loadGlobalConfig(Configurable $configurable) {
		$this->globalConfiguration()->load($configurable);
	}

	/**
	 * @param Configurable $configurable
	 */
	public function loadProjectConfig(Configurable $configurable) {
		$this->projectConfiguration()->load($configurable);
	}

	public function saveProjectConfig($configuration) {
		$this->projectConfiguration()->save($configuration);
	}
}