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


	/**
	 * @param string $serviceName
	 * @param array $service
	 * @return string[]
	 */
	function getSidekicksNames(string $serviceName, array $service);

	/**
	 * @param string $serviceName
	 * @param array $service
	 * @param array $services
	 * @return mixed
	 */
	function getSidekicks(string $serviceName, array $service, array $services);
}