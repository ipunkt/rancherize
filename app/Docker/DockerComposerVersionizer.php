<?php namespace Rancherize\Docker;

use Rancherize\Docker\DockerComposeParser\DockerComposeParserV1;
use Rancherize\Docker\DockerComposeParser\DockerComposeParserV2;
use Rancherize\Docker\DockerComposeReader\DockerComposeReader;
use Rancherize\Docker\DockerfileParser\DockerComposeParserVersion;
use Rancherize\Docker\Exceptions\UnkownDockerComposeVersion;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DockerComposerVersionizer
 * @package Rancherize\Docker
 */
class DockerComposerVersionizer {

	/**
	 * @var DockerComposeParserVersion[]
	 */
	protected $versions = [];

	/**
	 * DockerComposerVersionizer constructor.
	 */
	public function __construct() {
		$this->versions = [
			'1' => new DockerComposeParserV1(),
			'2' => new DockerComposeParserV2(),
		];
	}

	/**
	 * @param array $data
	 * @return DockerComposeParserVersion
	 */
	public function parse( array $data) {
		$version = '1';

		if(array_key_exists('version', $data) )
			$version = $data['version'];

		if( !array_key_exists($version, $this->versions))
			throw new UnkownDockerComposeVersion($version, array_keys($this->versions));

		return $this->versions[$version];
	}
}