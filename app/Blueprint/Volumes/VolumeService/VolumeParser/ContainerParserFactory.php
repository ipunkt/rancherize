<?php namespace Rancherize\Blueprint\Volumes\VolumeService\VolumeParser;

use Pimple\Container;

/**
 * Class ContainerParserFactory
 * @package Rancherize\Blueprint\Volumes\VolumeService\VolumeParser
 */
class ContainerParserFactory implements VolumeParserFactory {
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var string
	 */
	private $containerPath = 'volume-parser.';

	/**
	 * ContainerParserFactory constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container) {
		$this->container = $container;
	}

	/**
	 * @param $type
	 * @return VolumeParser
	 */
	public function getParser( $type ) {
		$containerPath = $this->containerPath.$type;

		return $this->container[$containerPath];
	}

	/**
	 * @param string $containerPath
	 */
	public function setContainerPath( string $containerPath ) {
		$this->containerPath = $containerPath;
	}
}