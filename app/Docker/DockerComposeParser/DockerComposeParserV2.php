<?php namespace Rancherize\Docker\DockerComposeParser;

use Rancherize\Docker\DockerfileParser\DockerComposeParserVersion;

/**
 * Class DockerComposeParserV2
 * @package Rancherize\Docker\DockerComposeParser
 */
class DockerComposeParserV2 implements DockerComposeParserVersion {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return array
	 */
	public function getService(string $serviceName, array $data) {
		if(!array_key_exists('services', $data))
			throw new NotFoundException('services field', 'services', array_keys($data));

		$services = $data['services'];

		foreach($services as $currentServiceName => $serviceData) {
			if( strtolower($serviceName) === strtolower($currentServiceName) )
				return $serviceData;
		}

		throw new NotFoundException('service', $serviceName, array_keys($services));
	}
}