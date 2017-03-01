<?php namespace Rancherize\Docker\DockerComposeParser;

use Rancherize\Docker\DockerComposeParser\Parsers\ServiceParserV2;
use Rancherize\Docker\DockerComposeParser\Parsers\SidekickNameParser;
use Rancherize\Docker\DockerComposeParser\Parsers\SidekickParser;
use Rancherize\Docker\DockerComposeParser\Parsers\VolumeNameSplitter;
use Rancherize\Docker\DockerComposeParser\Parsers\VolumeParser;
use Rancherize\Docker\DockerComposeParser\Parsers\VolumeSetter;
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
		$parser = new ServiceParserV2();
		return $parser->parse($serviceName, $data);
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
		$parser = new SidekickParser(new SidekickNameParser(), new ServiceParserV2());
		return $parser->parseSidekicks($serviceName, $service, $services);
	}

	/**
	 * Return the volumes set for this service in the form ['name' => 'internalPath']
	 *
	 * @param array $service
	 * @return array ['name' => 'internalPath']
	 */
	public function getVolumes(array $service) {
		$parser = new VolumeParser(new VolumeNameSplitter());
		return $parser->parse($service);
	}

	/**
	 * Set the given volumes for the service.
	 * volumes must be in the form ['name or external path' => 'internalPath']
	 *
	 * @param array $service
	 * @param string[] $volumes
	 */
	public function setVolumes(array &$service, array $volumes) {
		$setter = new VolumeSetter();
		$setter->set($service, $volumes);
	}
}