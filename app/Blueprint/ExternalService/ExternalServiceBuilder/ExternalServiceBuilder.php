<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceBuilder;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Configuration\Configuration;

/**
 * Interface ExternalServiceBuilder
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceBuilder
 */
interface ExternalServiceBuilder {

	/**
	 * @param $serviceName
	 * @param Configuration $serviceConfig
	 * @param Infrastructure $infrastructure
	 */
	function build( $serviceName, Configuration $serviceConfig, Infrastructure $infrastructure);
}