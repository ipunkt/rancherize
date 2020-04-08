<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class HealthcheckExtraInformation
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation
 */
class HealthcheckExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'healthcheck';

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * Number of successful attempts before the service is counted as healthy
	 *
	 * @var int
	 */
	protected $healthyThreshold;

	/**
	 * Number of failed attempts before the service is counted as unhealthy
	 *
	 * @var int
	 */
	protected $unhealthyThreshold;

	/**
	 * Number of milliseconds until the attempt is failed because of taking too long
	 *
	 * @var int
	 */
	protected $responseTimeout;

	/**
	 * Number of milliseconds before the first healthcheck is attempted.
	 * This is to give the container time to do initial work like connecting to databases etc
	 *
	 * @var int
	 */
	protected $initializingTimeout;

	/**
	 * Number of milliseconds before the first healthcheck is attempted after restarting the container.
	 * This is to give the container time to do initial work like connecting to databases etc
	 *
	 * @var int
	 */
	protected $reinitializingTimeout;

	/**
	 * Number of milliseconds between healthchecks
	 *
	 * @var int
	 */
	protected $interval;

	/**
	 * Rancher strategy 'report as unhealthy'
	 */
	const STRATEGY_NONE = 'none';

	/**
	 * Rancher strategy recreate unhealthy containers
	 * TODO: find recreate value
	 */
	const STRATEGY_RECREATE = 'recreate';

	/**
	 * Rancher strategy recreate unhealthy containers if at least X containers are still healthy
	 */
	const STRATEGY_RECREATE_ATLEAST = '';

	/**
	 * The way rancher responds to an unhealthy state
	 * STRATEGY_NONE - report the container / service as unhealthy and nothing else
	 * STRATEGY_RECREATE - recreate unhealthy containers
	 * STRATEGY_RECREATE_ATLEAST - recreate unhealthy containers if there are at least X containers still healthy
	 *
	 * @var string
	 */
	protected $strategy;

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return self::IDENTIFIER;
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort( $port ) {
		$this->port = $port;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl( string $url ) {
		$this->url = $url;
	}

	/**
	 * @return int
	 */
	public function getHealthyThreshold(): int {
		return $this->healthyThreshold;
	}

	/**
	 * @param int $healthyThreshold
	 */
	public function setHealthyThreshold( int $healthyThreshold ) {
		$this->healthyThreshold = $healthyThreshold;
	}

	/**
	 * @return int
	 */
	public function getUnhealthyThreshold(): int {
		return $this->unhealthyThreshold;
	}

	/**
	 * @param int $unhealthyThreshold
	 */
	public function setUnhealthyThreshold( int $unhealthyThreshold ) {
		$this->unhealthyThreshold = $unhealthyThreshold;
	}

	/**
	 * @return int
	 */
	public function getResponseTimeout(): int {
		return $this->responseTimeout;
	}

	/**
	 * @param int $responseTimeout
	 */
	public function setResponseTimeout( int $responseTimeout ) {
		$this->responseTimeout = $responseTimeout;
	}

	/**
	 * @return int
	 */
	public function getInitializingTimeout(): int {
		return $this->initializingTimeout;
	}

	/**
	 * @param int $initializingTimeout
	 */
	public function setInitializingTimeout( int $initializingTimeout ) {
		$this->initializingTimeout = $initializingTimeout;
	}

	/**
	 * @return int
	 */
	public function getReinitializingTimeout(): int {
		return $this->reinitializingTimeout;
	}

	/**
	 * @param int $reinitializingTimeout
	 */
	public function setReinitializingTimeout( int $reinitializingTimeout ) {
		$this->reinitializingTimeout = $reinitializingTimeout;
	}

	/**
	 * @return int
	 */
	public function getInterval(): int {
		return $this->interval;
	}

	/**
	 * @param int $interval
	 */
	public function setInterval( int $interval ) {
		$this->interval = $interval;
	}

	/**
	 * @return string
	 */
	public function getStrategy(): string {
		return $this->strategy;
	}

	/**
	 * @param string $strategy
	 */
	public function setStrategy( string $strategy ) {
		$this->strategy = $strategy;
	}

	public function setRecreateStrategy() {
		$this->strategy = self::STRATEGY_RECREATE;
	}

	public function setDoNothingStrategy() {
		$this->strategy = self::STRATEGY_NONE;
	}


}