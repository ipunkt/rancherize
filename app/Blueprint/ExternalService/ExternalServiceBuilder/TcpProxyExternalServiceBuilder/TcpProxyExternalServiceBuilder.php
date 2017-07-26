<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\TcpProxyExternalServiceBuilder;

use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\ExternalServiceBuilder;
use Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService\HealthcheckConfigurationToService;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\PublishUrls\PublishUrlsParser\PublishUrlsParser;
use Rancherize\Configuration\Configuration;

/**
 * Class TcpProxyExternalServiceBuilder
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\TcpProxyExternalServiceBuilder
 */
class TcpProxyExternalServiceBuilder implements ExternalServiceBuilder {

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

		$service->setImage( 'demandbase/docker-tcp-proxy' );
		$ip = $serviceConfig->get( 'ip' );
		$port = $serviceConfig->get( 'port', 80 );

		if( $this->healthcheckParser !== null )
			$this->healthcheckParser->parseToService( $service, $serviceConfig );

		if( $this->publishParser !== null )
			$this->publishParser->parseToService( $service, $serviceConfig );

		$service->setEnvironmentVariable('BACKEND_HOST', $ip);
		$service->setEnvironmentVariable('BACKEND_PORT', $port);
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