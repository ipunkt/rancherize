<?php namespace Rancherize\Configuration\Versions\StaticVersion;

use Rancherize\Configuration\Versions\ConfigurationVersionService;

/**
 * Class StaticConfigurationVersionService
 * @package Rancherize\Configuration\Versions
 */
class StaticConfigurationVersionService implements ConfigurationVersionService  {

	/**
	 * @var
	 */
	private $version;

	/**
	 * StaticConfigurationVersionService constructor.
	 * @param int|null $version defaults to 1
	 */
	public function __construct($version = null) {
		if($version === null)
			$version = 1;

		$this->version = $version;
	}

	/**
	 * @param $version
	 * @return bool
	 */
	public function isVersion( $version ) {
	}

	/**
	 * @param int $minVersion
	 * @param int $maxVersion
	 * @return bool
	 */
	public function versionRange( $minVersion, $maxVersion ) {

		if( $minVersion <= $this->version && $this->version <= $maxVersion )
			return true;

		return false;
	}

	/**
	 * @param $version
	 */
	public function setVersion( $version ) {
		$this->version = $version;
	}
}