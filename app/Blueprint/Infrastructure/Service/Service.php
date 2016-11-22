<?php namespace Rancherize\Blueprint\Infrastructure\Service;

/**
 * Class Service
 * @package Rancherize\Blueprint\Infrastructure
 */
class Service {
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $image = '';

	/**
	 * @var string[]
	 */
	protected $volumes = [];

	/**
	 * @var Service[]
	 */
	protected $volumesFrom = [];

	/**
	 * @var int[]
	 */
	protected $exposedPorts = [];

	const RESTART_UNLESS_STOPPED = 0;
	const RESTART_NEVER = 1;
	const RESTART_AWAYS = 1;

	/**
	 * @var int
	 */
	protected $restart = self::RESTART_UNLESS_STOPPED;

	/**
	 * @var bool
	 */
	protected $tty = false;

	/**
	 * @var string[]
	 */
	protected $environmentVariables = [];

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
	public function getImage(): string {
		return $this->image;
	}

	/**
	 * @param string $image
	 */
	public function setImage(string $image) {
		$this->image = $image;
	}

	/**
	 * @return \string[]
	 */
	public function getVolumes(): array {
		return $this->volumes;
	}

	/**
	 * @param $name
	 * @param $internalPath
	 */
	public function addVolume($name, $internalPath) {
		$this->volumes[$name] = $internalPath;
	}

	/**
	 * @return Service[]
	 */
	public function getVolumesFrom(): array {
		return $this->volumesFrom;
	}

	/**
	 * @param Service $service
	 */
	public function setVolumeFrom(Service $service) {
		$this->volumesFrom[] = $service;
	}

	/**
	 * @return \int[]
	 */
	public function getExposedPorts(): array {
		return $this->exposedPorts;
	}

	/**
	 * @param int $internalPort
	 * @param int $externalPort
	 */
	public function expose(int $internalPort, int $externalPort) {
		$this->exposedPorts[$internalPort] = $externalPort;
	}

	/**
	 * @return int
	 */
	public function getRestart(): int {
		return $this->restart;
	}

	/**
	 * @param int $restart
	 */
	public function setRestart(int $restart) {
		$this->restart = $restart;
	}

	/**
	 * @return boolean
	 */
	public function isTty(): bool {
		return $this->tty;
	}

	/**
	 * @param boolean $tty
	 */
	public function setTty(bool $tty) {
		$this->tty = $tty;
	}

	/**
	 * @return \string[]
	 */
	public function getEnvironmentVariables(): array {
		return $this->environmentVariables;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setEnvironmentVariable(string $name, string $value) {
		$this->environmentVariables[$name] = $value;
	}


}