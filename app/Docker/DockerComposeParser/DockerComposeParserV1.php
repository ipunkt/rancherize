<?php namespace Rancherize\Docker\DockerComposeParser;

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
}