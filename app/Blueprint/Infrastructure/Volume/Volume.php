<?php namespace Rancherize\Blueprint\Infrastructure\Volume;

/**
 * Class Volume
 * @package Rancherize\Blueprint\Infrastructure\Volume
 *
 * @deprecated Please use Rancherize\Blueprint\Infrastructure\Service\Volume
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
	 * @var string
	 */
	protected $external;

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

	/**
	 * @return bool
	 */
	public function hasExternal() : bool {
		return $this->external !== null;
	}

	/**
	 * @return string
	 */
	public function getExternal(): string {
		return $this->external;
	}

	/**
	 * @param string $external
	 */
	public function setExternal(string $external) {
		$this->external = $external;
	}


}