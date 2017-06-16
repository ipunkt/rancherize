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
		$healthcheckData = [
			'healthy_threshold' => $extraInformation->getHealthyThreshold(),
			'response_timeout' => $extraInformation->getResponseTimeout(),
			'port' => $extraInformation->getPort(),
			'unhealthy_threshold' => $extraInformation->getHealthyThreshold(),
			'initializing_timeout' => $extraInformation->getInitializingTimeout(),
			'interval' => $extraInformation->getInterval(),
			'strategy' => $extraInformation->getStrategy(),
			'reinitializing_timeout' => $extraInformation->getReinitializingTimeout(),
		];

		$url =$extraInformation->getUrl();
		$hasUrl = !empty( $url );
		if( $hasUrl )
			$healthcheckData['request_line'] = "GET \"$url\" \"HTTP/1.0\"";

		$rancherService['health_check'] = $healthcheckData;
	}
}