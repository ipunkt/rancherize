<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\RancherExternalServiceBuilder;

use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\ExternalServiceBuilder;
use Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation\ExternalServiceExtraInformation;
use Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService\HealthcheckConfigurationToService;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\PublishUrls\PublishUrlsParser\PublishUrlsParser;
use Rancherize\Configuration\Configuration;

/**
 * Class RancherExternalServiceBuilder
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\RancherExternalServiceBuilder
 */
class RancherExternalServiceBuilder implements ExternalServiceBuilder {
	/**
	 * @var HealthcheckConfigurationToService
	 */
	private $healthcheckParser;
	/**
	 * @var PublishUrlsParser
	 */
	private $publishParser;

	/**
	 * @param $serviceName
	 * @param Configuration $serviceConfig
	 * @param Infrastructure $infrastructure
	 * @return mixed
	 */
	public function build( $serviceName, Configuration $serviceConfig, Infrastructure $infrastructure ) {
		$service = new Service();
		$service->setImage( 'rancher/external-service' );
		$service->setName($serviceName);

		$ips = $serviceConfig->get( 'ips', [] );
		$externalServiceExtraInformation = new ExternalServiceExtraInformation();
		$externalServiceExtraInformation->setExternalIps( $ips );
		$service->addExtraInformation( $externalServiceExtraInformation );

		if( $this->healthcheckParser !== null )
			$this->healthcheckParser->parseToService( $service, $serviceConfig );

		if( $this->publishParser !== null )
			$this->publishParser->parseToService( $service, $serviceConfig );

		$infrastructure->addService( $service );
	}

	/**
	 * @param HealthcheckConfigurationToService $healthcheckParser
	 */
	public function setHealthcheckParser( HealthcheckConfigurationToService $healthcheckParser ) {
		$this->healthcheckParser = $healthcheckParser;
	}

	/**
	 * @param PublishUrlsParser $publishParser
	 */
	public function setPublishParser( PublishUrlsParser $publishParser ) {
		$this->publishParser = $publishParser;
	}

}