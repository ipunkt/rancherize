<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceBuilder;

/**
 * Interface ExternalServiceBuilderFactory
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceBuilder
 */
interface ExternalServiceBuilderFactory {

	/**
	 * @param $name
	 * @return ExternalServiceBuilder
	 */
	public function make( $name );

}