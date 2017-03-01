<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

/**
 * Class VolumeParser
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class VolumeParser {
	/**
	 * @var VolumeNameSplitter
	 */
	private $splitter;

	/**
	 * VolumeParser constructor.
	 * @param VolumeNameSplitter $splitter
	 */
	public function __construct(VolumeNameSplitter $splitter) {
		$this->splitter = $splitter;
	}

	/**
	 * @param array $service
	 * @return array
	 */
	public function parse(array $service) {
		$volumes = [];

		if( !array_key_exists('volumes', $service))
			return [];

		foreach($service['volumes'] as $key => $volume) {
			list($name, $volume) = $this->splitter->split($key, $volume);
			$volumes[$name] = $volume;
		}

		return $volumes;
	}
}