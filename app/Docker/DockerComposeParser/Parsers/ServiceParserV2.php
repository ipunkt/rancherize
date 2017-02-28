<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\Docker\DockerComposeParser\NotFoundException;

/**
 * Class ServiceParserV2
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class ServiceParserV2 implements ServiceParser {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return mixed
	 */
	public function parse(string $serviceName, array $data) {
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