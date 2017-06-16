<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckInitService;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigurationInitializer;

/**
 * Class HealthcheckInitService
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckInitService
 */
class HealthcheckInitService {
	/**
	 * @var ConfigurationInitializer
	 */
	private $initializer;

	/**
	 * HealthcheckInitService constructor.
	 * @param ConfigurationInitializer $initializer
	 */
	public function __construct( ConfigurationInitializer $initializer) {
		$this->initializer = $initializer;
	}

	/**
	 * @param Configurable $configurable
	 */
	public function init( Configurable $configurable ) {

		$healthcheckConfigurable = new PrefixConfigurableDecorator($configurable, 'healthcheck.');

		$this->initializer->init($healthcheckConfigurable, 'enable', false);
		$this->initializer->init($healthcheckConfigurable, 'url', false);

	}

}