<?php namespace Rancherize\Docker\DockerComposeParser;

use Rancherize\Docker\DockerComposeParser\Parsers\SidekickNameParser;
use Rancherize\Docker\DockerComposeParser\Parsers\SidekickParser;
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

	/**
	 * @param string $serviceName
	 * @param array $service
	 * @return string[]
	 */
	public function getSidekicksNames(string $serviceName, array $service) {
		$parser = new SidekickNameParser();
		return $parser->parseNames($serviceName, $service);
	}

	/**
	 * @param string $serviceName
	 * @param array $service
	 * @param array $services
	 * @return array
	 */
	public function getSidekicks(string $serviceName, array $service, array $services) {
		$parser = new SidekickParser(new SidekickNameParser(), container('by-key-service'));
		return $parser->parseSidekicks($serviceName, $service, $services);
	}
}