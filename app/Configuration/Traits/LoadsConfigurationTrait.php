<?php namespace Rancherize\Configuration\Traits;
use Rancherize\Configuration\Configurable;

/**
 * Trait LoadsConfigurationTrait
 * @package Rancherize\Configuration\Traits
 *
 * Loads the global and the project configuration
 */
trait LoadsConfigurationTrait {

	/**
	 * @var Configurable
	 */
	protected $configuration;

	/**
	 * @param Configurable $configurable
	 */
	public function setConfiguration(Configurable $configurable) {
		$this->configuration = $configurable;
	}

	/**
	 * @return Configurable
	 */
	protected function getConfiguration(  ) {
		return $this->configuration;
	}
}