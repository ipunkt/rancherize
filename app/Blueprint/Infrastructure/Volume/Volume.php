<?php namespace Rancherize\Blueprint\Infrastructure\Volume;

/**
 * Class Volume
 * @package Rancherize\Blueprint\Infrastructure\Volume
 */
class Volume {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $driver;

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getDriver() {
		return $this->driver;
	}

	/**
	 * @param string $driver
	 */
	public function setDriver(string $driver) {
		$this->driver = $driver;
	}



}