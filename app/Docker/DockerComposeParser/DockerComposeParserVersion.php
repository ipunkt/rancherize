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

	/**
	 * Return the volumes set for this service in the form ['name' => 'internalPath']
	 *
	 * @param array $service
	 * @return array ['name' => 'internalPath']
	 */
	function getVolumes(array $service);

	/**
	 * Set the given volumes for the service.
	 * volumes must be in the form ['name or external path' => 'internalPath']
	 *
	 * @param array $service
	 * @param string[] $volumes
	 */
	function setVolumes(array &$service, array $volumes);
}