<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceParser;

use Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation\ExternalServiceExtraInformation;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class ExternalServiceParser
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceParser
 */
class ExternalServiceParser {

	/**
	 * @param Configuration $configuration
	 * @param Infrastructure $infrastructure
	 */
	public function parse( Configuration $configuration, Infrastructure $infrastructure ) {

		if( !$configuration->has('external-services') )
			return;

		$externalServiceNames = $configuration->get('external-services', []);
		if(! is_array($externalServiceNames))
			return;

		foreach($externalServiceNames as $serviceName) {
			$serviceConfig = new PrefixConfigurationDecorator($configuration, 'external-services.'.$serviceName.'.');

			$this->buildService( $serviceConfig , $infrastructure );
		}

	}

	/**
	 * @param Configuration $serviceConfig
	 * @param Infrastructure $infrastructure
	 */
	private function buildService(  Configuration $serviceConfig, Infrastructure $infrastructure ) {
		$service = new Service();
		$service->setImage( 'rancher/external-service' );

		$ips = $serviceConfig->get( 'ips', [] );
		$externalServiceExtraInformation = new ExternalServiceExtraInformation();
		$externalServiceExtraInformation->setExternalIps( $ips );
		$service->addExtraInformation( $externalServiceExtraInformation );

		/**
		 * TODO: add publish and healthcheck service infos
		 */

		$infrastructure->addService( $service );
	}
}