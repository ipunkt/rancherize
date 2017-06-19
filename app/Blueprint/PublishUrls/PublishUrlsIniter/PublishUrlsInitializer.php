<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsIniter;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigurationInitializer;

/**
 * Class PublishUrlsIniter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsIniter
 */
class PublishUrlsInitializer {
	/**
	 * @var ConfigurationInitializer
	 */
	private $initializer;

	/**
	 * PublishUrlsInitializer constructor.
	 * @param ConfigurationInitializer $initializer
	 */
	public function __construct( ConfigurationInitializer $initializer) {
		$this->initializer = $initializer;
	}

	/**
	 * @param Configurable $environmentSetter
	 * @param Configurable $projectSetter
	 */
	public function init( Configurable $environmentSetter, Configurable $projectSetter = null ) {

		if($projectSetter === null)
			$projectSetter = $environmentSetter;

		$publishEnvironmentConfigurable = new PrefixConfigurableDecorator($environmentSetter, 'publish.');
		$publishProjectConfigurable = new PrefixConfigurableDecorator($projectSetter, 'publish.');

		$this->initializer->init($publishEnvironmentConfigurable, 'enable', false, $publishProjectConfigurable);
		$this->initializer->init($publishEnvironmentConfigurable, 'url', 'https://www.example.com/');

	}

}