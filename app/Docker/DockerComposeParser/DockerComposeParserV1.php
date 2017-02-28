<?php namespace Rancherize\Docker\DockerComposeParser;

use Rancherize\Docker\DockerComposeParser\Parsers\SidekickNameParser;
use Rancherize\Docker\DockerComposeParser\Parsers\SidekickParser;
use Rancherize\Docker\DockerfileParser\DockerComposeParserVersion;

/**
 * Class DockerComposeParserV1
 * @package Rancherize\Docker\DockerComposeParser
 */
class DockerComposeParserV1 implements DockerComposeParserVersion {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return array
	 */
	public function getService(string $serviceName, array $data) {

		foreach($data as $currentStackName => $stackData) {
			if(strtolower($serviceName) === strtolower($currentStackName))
				return $stackData;
		}

		throw new NotFoundException('stack', $serviceName, $data);
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
	 * @return mixed
	 */
	public function getSidekicks(string $serviceName, array $service, array $services) {
		$parser = new SidekickParser(new SidekickNameParser(), container('by-key-service'));
		return $parser->parseSidekicks($serviceName, $service, $services);
	}
}