<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

/**
 * Interface ServiceParser
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
interface ServiceParser {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return mixed
	 */
	function parse(string $serviceName, array $data);

}