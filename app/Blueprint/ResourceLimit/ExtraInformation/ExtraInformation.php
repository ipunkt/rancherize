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
	 * @var float
	 */
	protected $memoryLimit = null;

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
	 * @return float
	 */
	public function getMemoryLimit(): float {
		return $this->memoryLimit;
	}

	/**
	 * @param float $memoryLimit
	 */
	public function setMemoryLimit( $memoryLimit ) {
		$this->memoryLimit = $memoryLimit;
	}


}