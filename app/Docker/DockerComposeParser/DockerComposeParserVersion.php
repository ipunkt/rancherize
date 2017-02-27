<?php namespace Rancherize\Docker\DockerfileParser;

/**
 * Interface DockerComposeParserVersion
 * @package Rancherize\Docker\DockerComposerVersionizer
 */
interface DockerComposeParserVersion {

	/**
	 * @param string $stackName
	 * @param array $data
	 * @return array
	 */
	function getService(string $stackName, array $data);
}