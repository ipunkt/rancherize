<?php namespace Rancherize\Blueprint\ResourceLimit\ExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class ResourceLimitExtraInformation
 * @package Rancherize\Blueprint\ResourceLimit\ResourceLimitExtraInformation
 */
class ExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'resource-limit';

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return self::IDENTIFIER;
	}

	/**
	 * @var int
	 */
	protected $cpuPeriod = null;

	/**
	 * @var int
	 */
	protected $cpuQuota = null;

	/**
	 * Cpu limit in mCPU - thousandth of cpu
	 * 1000 = one full cpu
	 *
	 * @var null|int
	 */
	protected $cpuReservation = null;

	/**
	 * @var int
	 */
	protected $memoryLimit = null;

	/**
	 * @var null|int
	 */
	protected $memoryReservation = null;

	/**
	 * @return int
	 */
	public function getCpuPeriod() {
		return $this->cpuPeriod;
	}

	/**
	 * @param int $cpuPeriod
	 */
	public function setCpuPeriod( $cpuPeriod ) {
		$this->cpuPeriod = $cpuPeriod;
	}

	/**
	 * @return int
	 */
	public function getCpuQuota() {
		return $this->cpuQuota;
	}

	/**
	 * @param int $cpuQuota
	 */
	public function setCpuQuota( $cpuQuota ) {
		$this->cpuQuota = $cpuQuota;
	}

	/**
	 * @return int
	 */
	public function getMemoryLimit() {
		return $this->memoryLimit;
	}

	/**
	 * @param int $memoryLimit
	 */
	public function setMemoryLimit( $memoryLimit ) {
		$this->memoryLimit = $memoryLimit;
	}

	/**
	 * @return null
	 */
	public function getMemoryReservation() {
		return $this->memoryReservation;
	}

	/**
	 * @param null $memoryReservation
	 */
	public function setMemoryReservation( $memoryReservation ) {
		$this->memoryReservation = $memoryReservation;
	}

	/**
	 * @return null
	 */
	public function getCpuReservation() {
		return $this->cpuReservation;
	}

	/**
	 * @param null $cpuReservation
	 */
	public function setCpuReservation( $cpuReservation ) {
		$this->cpuReservation = $cpuReservation;
	}



}