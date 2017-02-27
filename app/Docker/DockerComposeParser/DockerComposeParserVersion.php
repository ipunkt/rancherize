<?php namespace Rancherize\Docker\DockerfileParser;

/**
 * Interface DockerComposeParserVersion
 * @package Rancherize\Docker\DockerComposerVersionizer
 */
interface DockerComposeParserVersion {

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return array
	 */
	function getService(string $serviceName, array $data);
}