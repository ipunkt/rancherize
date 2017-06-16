<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckDefaultInformationSetter;
use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class HealthcheckConfigurationToService
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService
 */
class HealthcheckConfigurationToService {
	/**
	 * @var HealthcheckDefaultInformationSetter
	 */
	private $defaultInformationSetter;

	/**
	 * HealthcheckConfigurationToService constructor.
	 * @param HealthcheckDefaultInformationSetter $defaultInformationSetter
	 */
	public function __construct( HealthcheckDefaultInformationSetter $defaultInformationSetter) {
		$this->defaultInformationSetter = $defaultInformationSetter;
	}

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 */
	public function parseToService( Service $service, Configuration $configuration ) {

		$hasHealthcheckSet = is_array( $configuration->get( 'healthcheck', false ) );
		if( !$hasHealthcheckSet )
			return;

		$healthcheckConfig = new PrefixConfigurationDecorator($configuration, 'healthcheck.');

		// allows to disable healthchecks for debug purposes without removing the information
		if( !$healthcheckConfig->get('enable', true) )
			return;

		$healthcheckInformation = new HealthcheckExtraInformation();
		$this->defaultInformationSetter->setDefaults( $healthcheckInformation );

		$url = $healthcheckConfig->get('url', '');
		$healthcheckInformation->setUrl( $url );

		$service->addExtraInformation($healthcheckInformation);

	}
}