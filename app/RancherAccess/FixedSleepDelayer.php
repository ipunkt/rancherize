<?php namespace Rancherize\RancherAccess;

/**
 * Class FixedSleepDelayer
 * @package Rancherize\RancherAccess
 */
class FixedSleepDelayer implements Delayer {
	/**
	 * @var int
	 */
	private $microSeconds;

	/**
	 * FixedSleepDelayer constructor.
	 * @param int $microSeconds
	 */
	public function __construct(int $microSeconds) {
		$this->microSeconds = $microSeconds;
	}

	/**
	 * @param int $run
	 */
	public function delay(int $run) {
		usleep($this->microSeconds);
	}
}