<?php namespace Rancherize\Docker\Exceptions;

/**
 * Class UnkownDockerComposeVersion
 * @package Rancherize\Docker\Exceptions
 */
class UnkownDockerComposeVersion extends DockerException {
	/**
	 * @var string
	 */
	private $version;
	/**
	 * @var int
	 */
	private $availableVersions;

	/**
	 * UnkownDockerComposeVersion constructor.
	 * @param string $version
	 * @param array $availableVersions
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($version, array $availableVersions, int $code = 0, \Exception $e = null) {
		$this->version = $version;
		$this->availableVersions = $availableVersions;
		$versionNames = implode(',', $availableVersions);
		parent::__construct("Failed to parse docker-compose.yml. Unexpected version: $version. Known versions: $versionNames", $code, $e);
	}
}