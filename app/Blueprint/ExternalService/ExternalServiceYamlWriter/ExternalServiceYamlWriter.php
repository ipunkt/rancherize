<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceYamlWriter;
use Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation\ExternalServiceExtraInformation;

/**
 * Class ExternalServiceYamlWriter
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceYamlWriter
 */
class ExternalServiceYamlWriter {

	/**
	 * @param $fileVersion
	 * @param ExternalServiceExtraInformation $information
	 * @param $rancherData
	 */
	public function write( $fileVersion, ExternalServiceExtraInformation $information, &$rancherData ) {
		$rancherData['external_ips'] = $information->getExternalIps();
	}
}