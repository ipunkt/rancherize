<?php namespace Rancherize\Blueprint\Volumes\VolumeService;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class VolumeService
 * @package Rancherize\Blueprint\Volumes\VolumeService
 *
 * This service parses the configuration for volumes and adds them to the main service
 */
class VolumeService {

	/**
	 * Parse configuration and add volumes
	 *
	 * @param Configuration $configuration
	 * @param Service $mainService
	 */
	public function parse( Configuration $configuration, Service $mainService ) {

		if( !$configuration->has('volumes') )
			return;

	}

}