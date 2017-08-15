<?php namespace Rancherize\Composer;

/**
 * Class PackageName
 * @package Rancherize\Composer
 */
class PackageName {
	/**
	 * @var string
	 */
	protected $provider = '';

	/**
	 * @var string
	 */
	protected $packageName = '';

	/**
	 * @var string
	 */
	protected $versionRestraint = '';

	/**
	 * @return mixed
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * @param mixed $provider
	 */
	public function setProvider( $provider ) {
		$this->provider = $provider;
	}

	/**
	 * @return mixed
	 */
	public function getPackageName() {
		return $this->packageName;
	}

	/**
	 * @param mixed $packageName
	 */
	public function setPackageName( $packageName ) {
		$this->packageName = $packageName;
	}

	/**
	 * @return mixed
	 */
	public function getVersionRestraint() {
		return $this->versionRestraint;
	}

	/**
	 * @param mixed $versionRestraint
	 */
	public function setVersionConstraint( $versionRestraint ) {
		$this->versionRestraint = $versionRestraint;
	}

}