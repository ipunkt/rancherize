<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceParser;
use Rancherize\Configuration\Configuration;

/**
 * Class ExternalServiceNameParser
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceParser
 */
class ExternalServiceNameParser {

	/**
	 * @param Configuration $configuration
	 * @return string[]
	 */
	public function parseNames( Configuration $configuration ) {

		if( !$configuration->has('external-services') )
			return [];

		$externalServices = $configuration->get('external-services', []);
		if(! is_array($externalServices))
			return [];
		$externalServiceNames = array_keys($externalServices);

		return $externalServiceNames;
	}

}