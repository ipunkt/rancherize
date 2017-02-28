<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\Docker\DockerComposeParser\NotFoundException;

/**
 * Class ServiceParserV1
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class ServiceParserV1 implements ServiceParser {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return mixed
	 */
	public function parse(string $serviceName, array $data) {
		foreach($data as $currentStackName => $stackData) {
			if(strtolower($serviceName) === strtolower($currentStackName))
				return $stackData;
		}

		throw new NotFoundException('stack', $serviceName, $data);
	}
}