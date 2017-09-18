<?php namespace Rancherize\Configuration\Versions\StaticVersion;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Events\ConfigurationLoadedEvent;

/**
 * Class ConfigurationEventHandler
 * @package Rancherize\Configuration\Versions\StaticVersion
 */
class ConfigurationEventHandler {

	/**
	 * @var StaticConfigurationVersionService
	 */
	private $staticConfigurationService;

	/**
	 * @param StaticConfigurationVersionService $staticConfigurationService
	 */
	public function setStaticConfigurationService( StaticConfigurationVersionService $staticConfigurationService ) {
		$this->staticConfigurationService = $staticConfigurationService;
	}


	/**
	 * @param Configuration $configuration
	 */
	public function configurationLoaded( ConfigurationLoadedEvent $event ) {
		$configuration = $event->getConfiguration();

		$version = intval( $configuration->get('project.version', 1) );

		$this->staticConfigurationService->setVersion($version);
	}

}