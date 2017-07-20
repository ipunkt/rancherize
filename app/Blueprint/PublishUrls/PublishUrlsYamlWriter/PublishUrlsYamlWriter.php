<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter;
use Pimple\Container;
use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;

/**
 * Class PublishUrlsYamlWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter
 *
 * Instantiates a PublishUrlsYamlWriterVersion from the container to write a services publish information to a docker file
 */
class PublishUrlsYamlWriter {

	const CONTAINER_PREFIX = 'publish-urls-yaml-writer';

	/**
	 * @var string
	 */
	protected $defaultType = 'traefik';

	/**
	 * @var int
	 */
	protected  $defaultVersion = 2;

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * PublishUrlsYamlWriter constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container) {
		$this->container = $container;
	}

	/**
	 * @param $version
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	public function write( $version, PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$type = $extraInformation->getType();
		if( empty($type) )
			$type = $this->defaultType;

		try {
			$key = implode('.', [self::CONTAINER_PREFIX, $type, $version]);
			/**
			 * @var PublishUrlsYamlWriterVersion $version
			 */
			$version = $this->container[$key];
		} catch(\InvalidArgumentException $e) {
			$key = implode('.', [self::CONTAINER_PREFIX, $type, $this->defaultVersion]);

			/**
			 * @var PublishUrlsYamlWriterVersion $version
			 */
			$version = $this->container[$key];
		}

		$version->write($extraInformation, $dockerService);
	}

	/**
	 * @param $defaultType
	 */
	public function setDefaultType($defaultType) {
		$this->defaultType = $defaultType;
	}

}