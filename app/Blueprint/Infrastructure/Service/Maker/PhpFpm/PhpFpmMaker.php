<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PhpFpmMaker
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker
 */
class PhpFpmMaker {

	/**
	 * @var PhpVersion[]
	 */
	protected $phpVersions = [];

	/**
	 * @var int
	 */
	protected $defaultVersion = null;

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		if( empty($this->phpVersions) )
			throw new NoPhpVersionsAvailableException;

		$phpVersion = $config->get('php', '7.0');

		if( !array_key_exists($phpVersion, $this->phpVersions) ) {
			throw new PhpVersionNotAvailableException($phpVersion);
		}

		$this->phpVersions[$phpVersion]->make($config, $mainService, $infrastructure);
	}

	/**
	 * Add a PhPVersion.
	 * The version will be made default if it is the first version set or if the $default parameter is set to true
	 *
	 * @param PhpVersion $version
	 * @return $this
	 */
	public function addVersion(PhpVersion $version) {
		$versionString = $version->getVersion();

		$this->phpVersions[$versionString] = $version;

		return $this;
	}
}