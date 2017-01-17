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
	 * @var string|Service
	 */
	private $appTarget;

	/**
	 * @param string $hostDirectory
	 * @param string $containerDirectory
	 */
	public function setAppMount(string $hostDirectory, string $containerDirectory) {
		$this->appTarget = [$hostDirectory, $containerDirectory];
	}

	/**
	 * @param Service $service
	 */
	public function setAppService(Service $service) {
		$this->appTarget = $service;
	}

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		if( empty($this->phpVersions) )
			throw new NoPhpVersionsAvailableException;

		$phpVersionString = $config->get('php', '7.0');

		if( !array_key_exists($phpVersionString, $this->phpVersions) )
			throw new PhpVersionNotAvailableException($phpVersionString);

		$phpVersion= $this->phpVersions[$phpVersionString];

		$this->setAppSource($phpVersion);

		$phpVersion->make($config, $mainService, $infrastructure);
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

	/**
	 * @param $phpVersion
	 */
	protected function setAppSource(PhpVersion $phpVersion) {
		$appTarget = $this->appTarget;

		if ($appTarget instanceof Service) {
			$phpVersion->setAppService($appTarget);
			return;
		}

		list($hostDirectory, $containerDirectory) = $appTarget;
		$phpVersion->setAppMount($hostDirectory, $containerDirectory);
	}
}