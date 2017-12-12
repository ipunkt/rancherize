<?php namespace Rancherize\Blueprint\Volumes\VolumeService;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\Volume;
use Rancherize\Blueprint\Volumes\VolumeService\VolumeParser\VolumeParserFactory;
use Rancherize\Configuration\Configuration;

/**
 * Class VolumeService
 * @package Rancherize\Blueprint\Volumes\VolumeService
 *
 * This service parses the configuration for volumes and adds them to the main service
 */
class VolumeService {
	/**
	 * @var VolumeParserFactory
	 */
	private $volumeParserFactory;

	/**
	 * @var bool
	 */
	protected $onSidekicks = false;

	/**
	 * VolumeService constructor.
	 * @param VolumeParserFactory $volumeParserFactory
	 */
	public function __construct( VolumeParserFactory $volumeParserFactory) {
		$this->volumeParserFactory = $volumeParserFactory;
	}

	/**
	 * Parse configuration and add volumes
	 *
	 * @param Configuration $configuration
	 * @param Service $mainService
	 */
	public function parse( Configuration $configuration, Service $mainService ) {

		if( !$configuration->has('volumes') )
			return;

		$volumesDefinition = $configuration->get( 'volumes' );
		foreach( $volumesDefinition as $key => $data ) {

			$type = 'object';
			if( is_string($data) )
				$type = 'string';

			$volumeParser = $this->volumeParserFactory->getParser( $type );
			$volume = $volumeParser->parse($key, $data);
			$mainService->addVolume($volume);

			$this->applyToSidekicks($mainService, $volume);
		}

	}

	/**
	 * Setting this to true will cause the volumes to be set on the services sidekick as well
	 *
	 * @param bool $onSidekicks
	 */
	public function setOnSidekicks( bool $onSidekicks ) {
		$this->onSidekicks = $onSidekicks;
	}

	/**
	 * Applies the volumes to sidekicks of the mainService
	 *
	 * In Rancher this could be done through volumes-from
	 * with docker-compose volumes-from tends to create circular dependency problems
	 *
	 * @param Service $mainService
	 * @param Volume $volume
	 */
	private function applyToSidekicks( Service $mainService, Volume $volume ) {
		if( ! $this->onSidekicks )
			return;

		foreach( $mainService->getSidekicks() as $sidekick )
			$sidekick->addVolume($volume);
	}

}