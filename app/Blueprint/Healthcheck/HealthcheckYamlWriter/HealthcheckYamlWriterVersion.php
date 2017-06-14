<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;

/**
 * Interface HealthcheckYamlWriterVersion
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter
 */
interface HealthcheckYamlWriterVersion {

	/**
	 * @param HealthcheckExtraInformation $extraInformation
	 * @param array $rancherService
	 */
	function write( HealthcheckExtraInformation $extraInformation, array &$rancherService );
}