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
	 * @param Configurable $config
	 * @param Configurable $setter
	 */
	public function init( Configurable $config, Configurable $setter = null ) {

		$publishConfigurable = new PrefixConfigurableDecorator($config, 'publish.');
		$publishSetterConfigurable = new PrefixConfigurableDecorator($setter, 'publish.');

		$this->initializer->init($publishConfigurable, 'publish.enable', false, $publishSetterConfigurable);
		$this->initializer->init($publishConfigurable, 'publish.url', 'https://www.example.com/path', $publishSetterConfigurable);

	}

}