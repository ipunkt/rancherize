<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceParser;

use Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation\ExternalServiceExtraInformation;
use Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService\HealthcheckConfigurationToService;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\PublishUrls\PublishUrlsParser\PublishUrlsParser;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class ExternalServiceParser
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceParser
 */
class ExternalServiceParser {

	/**
	 * @var HealthcheckConfigurationToService
	 */
	protected $healthcheckParser;

	/**
	 * @var PublishUrlsParser
	 */
	protected $publishParser;

	/**
	 * @param Configuration $configuration
	 * @param Infrastructure $infrastructure
	 */
	public function parse( Configuration $configuration, Infrastructure $infrastructure ) {

		if( !$configuration->has('external-services') )
			return;

		$externalServices = $configuration->get('external-services', []);
		if(! is_array($externalServices))
			return;
		$externalServiceNames = array_keys($externalServices);


		foreach($externalServiceNames as $serviceName) {
			$serviceConfig = new PrefixConfigurationDecorator($configuration, 'external-services.'.$serviceName.'.');

			$this->buildService( $serviceName, $serviceConfig , $infrastructure );
		}

	}

	/**
	 * @param Configuration $serviceConfig
	 * @param Infrastructure $infrastructure
	 */
	private function buildService( $serviceName , Configuration $serviceConfig, Infrastructure $infrastructure ) {
		$service = new Service();
		$service->setImage( 'rancher/external-service' );

		if( is_numeric($serviceName) )
			$serviceName = 'external-'.$serviceName;

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