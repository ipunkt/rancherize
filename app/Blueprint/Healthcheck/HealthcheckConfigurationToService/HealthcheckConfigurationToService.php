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

		$port = $healthcheckConfig->get('port', 80);
		$healthcheckInformation->setPort( $port );

		$strategy = $healthcheckConfig->get( 'strategy', HealthcheckExtraInformation::STRATEGY_NONE );
		$healthcheckInformation->setStrategy( $strategy );

		$interval = $healthcheckConfig->get( 'interval', 2000 );
		$healthcheckInformation->setInterval( $interval );

		$responseTimeout = $healthcheckConfig->get( 'response-timeout', 2000 );
		$healthcheckInformation->setResponseTimeout( $responseTimeout );

		$initializingTimeout = $healthcheckConfig->get( 'init-timeout', 60000 );
		$healthcheckInformation->setInitializingTimeout( $initializingTimeout );

		$reinitializingTimeout = $healthcheckConfig->get( 'reinit-timeout', 60000 );
		$healthcheckInformation->setReinitializingTimeout( $reinitializingTimeout );

		$healthyThreshold = $healthcheckConfig->get( 'healthy-threshold', 2 );
		$healthcheckInformation->setHealthyThreshold( $healthyThreshold );

		$unhealthyThreshold = $healthcheckConfig->get( 'unhealthy-threshold', 3 );
		$healthcheckInformation->setUnhealthyThreshold( $unhealthyThreshold );

		$service->addExtraInformation($healthcheckInformation);

	}
}