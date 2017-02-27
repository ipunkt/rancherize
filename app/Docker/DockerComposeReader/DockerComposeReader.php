<?php namespace Rancherize\Docker\DockerComposeReader;

use Symfony\Component\Yaml\Yaml;

/**
 * Class DockerComposeReader
 * @package Rancherize\Docker\DockerComposeReader
 *
 * Reads a docker-compose.yml and returns the content as array
 */
class DockerComposeReader {
	/**
	 * @param $fileContent
	 * @return array
	 */
	public function read($fileContent) {
		return Yaml::parse($fileContent);
	}
}