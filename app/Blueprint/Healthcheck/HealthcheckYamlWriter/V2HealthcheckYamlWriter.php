<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;

/**
 * Class V2HealthcheckYamlWriter
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter
 */
class V2HealthcheckYamlWriter implements HealthcheckYamlWriterVersion {

	/**
	 * @param HealthcheckExtraInformation $extraInformation
	 * @param array $rancherService
	 */
	public function write( HealthcheckExtraInformation $extraInformation, array &$rancherService ) {
	}
}