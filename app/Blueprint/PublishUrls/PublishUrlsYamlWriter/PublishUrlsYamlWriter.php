<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\V2TraefikPublishUrlsYamlWriterVersion;

/**
 * Class PublishUrlsYamlWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter
 */
class PublishUrlsYamlWriter {

	/**
	 * @var string
	 */
	protected $defaultType = 'traefik';

	/**
	 * @var int
	 */
	protected  $defaultVersion = 2;

	/**
	 * @var PublishUrlsYamlWriterVersion[][]
	 */
	protected $versions = [
		'traefik' => [

		],
	];

	public function __construct() {
		$this->versions['traefik'][2] = new V2TraefikPublishUrlsYamlWriterVersion();
	}

	/**
	 * @param $version
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	public function write( $version, PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$type = $extraInformation->getType();
		if($type === null)
			$type = $this->defaultType;

		$writerTypeExists = array_key_exists( $type, $this->versions );
		if($type !== null && !$writerTypeExists )
			$type = $this->defaultType;

		if( !array_key_exists($version, $this->versions[$type]) ) {
			/**
			 * TODO: Warning
			 */
			$version = $this->defaultVersion;
		}


		$version = $this->versions[$type][$version];

		$version->write($extraInformation, $dockerService);
	}

}