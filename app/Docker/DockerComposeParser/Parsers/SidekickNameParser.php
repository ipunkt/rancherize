<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\Docker\DockerComposeParser\NotFoundException;

/**
 * Class SidekickNameParser
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class SidekickNameParser {

	/**
	 * @param string $serviceName
	 * @param array $service
	 * @return string[]
	 */
	public function parseNames(string $serviceName, array $service) {
		if(!array_key_exists('labels', $service))
			throw new NotFoundException('labels', $serviceName, array_keys($service));

		$labels = $service['labels'];
		if(! array_key_exists('io.rancher.sidekicks', $labels) )
			throw new NotFoundException('io.rancher.sidekicks', $serviceName, array_keys($labels));

		$sidekickNames = explode(',', $labels['io.rancher.sidekicks']);

		return $sidekickNames;
	}
}