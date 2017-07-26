<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceBuilder;
use Pimple\Container;

/**
 * Class ContainerExternalServiceFactory
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceBuilder
 */
class ContainerExternalServiceFactory implements ExternalServiceBuilderFactory {

	/**
	 * @var string
	 */
	protected $prefix = 'external-service-builder.builder-types.';
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * ContainerExternalServiceFactory constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container) {
		$this->container = $container;
	}

	/**
	 * @param $name
	 * @return ExternalServiceBuilder
	 */
	public function make( $name ) {
		$key = $this->prefix . $name;

		return $this->container[$key];
	}
}