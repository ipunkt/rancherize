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

		$configs = $configuration->get('external-services', []);
		if(! is_array($configs))
			return [];
		$configNames = array_keys($configs);

		$externalServiceNames = [];
		foreach($configNames as $externalServiceName) {
			$serviceKey = $externalServiceName;

			if( is_numeric($externalServiceName) )
				$externalServiceName = 'external-'.$externalServiceName;

			$externalServiceNames[$serviceKey] = $externalServiceName;
		}


		return $externalServiceNames;
	}

}