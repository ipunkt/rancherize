<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceParser;

use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\ExternalServiceBuilderFactory;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class ExternalServiceParser
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceParser
 */
class ExternalServiceParser {

	/**
	 * @var ExternalServiceNameParser
	 */
	private $nameParser;
	/**
	 * @var ExternalServiceBuilderFactory
	 */
	private $builderFactory;

	/**
	 * @var string
	 */
	private $defaultBuilder = 'rancher-external';

	/**
	 * ExternalServiceParser constructor.
	 * @param ExternalServiceNameParser $nameParser
	 * @param ExternalServiceBuilderFactory $builderFactory
	 */
	public function __construct( ExternalServiceNameParser $nameParser, ExternalServiceBuilderFactory $builderFactory) {
		$this->builderFactory = $builderFactory;
		$this->nameParser = $nameParser;
	}

	/**
	 * @param Configuration $configuration
	 * @param Infrastructure $infrastructure
	 */
	public function parse( Configuration $configuration, Infrastructure $infrastructure ) {

		/**
		 * Allow setting enable to false to disable all services without having to delete the definition
		 */
		if( !$configuration->get('external-services.enable', true) )
			return;

		$externalServiceNames = $this->nameParser->parseNames( $configuration );

		foreach($externalServiceNames as $serviceKey => $serviceName) {
			$serviceConfig = new PrefixConfigurationDecorator($configuration, 'external-services.'.$serviceKey.'.');

			$this->buildService( $serviceName, $serviceConfig , $infrastructure );
		}

	}

	/**
	 * @param Configuration $serviceConfig
	 * @param Infrastructure $infrastructure
	 */
	private function buildService( $serviceName , Configuration $serviceConfig, Infrastructure $infrastructure ) {
		$type = $serviceConfig->get( 'type', $this->defaultBuilder );

		$builder = $this->builderFactory->make( $type );
		$builder->build($serviceName, $serviceConfig, $infrastructure);

	}

	/**
	 * @param string $defaultBuilder
	 */
	public function setDefaultBuilder( string $defaultBuilder ) {
		$this->defaultBuilder = $defaultBuilder;
	}

}