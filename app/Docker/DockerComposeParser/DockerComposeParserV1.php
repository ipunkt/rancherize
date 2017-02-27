<?php namespace Rancherize\Docker\DockerComposeParser;

use Rancherize\Docker\DockerfileParser\DockerComposeParserVersion;
use Rancherize\RancherAccess\ByNameService;

/**
 * Class DockerComposeParserV1
 * @package Rancherize\Docker\DockerComposeParser
 */
class DockerComposeParserV1 implements DockerComposeParserVersion {
	/**
	 * @var ByNameService
	 */
	private $byNameService;

	/**
	 * @param string $stackName
	 * @param array $data
	 * @return array
	 */
	public function getService(string $stackName, array $data) {

		foreach($data as $currentStackName => $stackData) {
			if(strtolower($stackName) === strtolower($currentStackName))
				return $stackData;
		}

		throw new NotFoundException('stack', $stackName, $data);
	}
}