<?php namespace Rancherize\Blueprint\Scheduler\SchedulerExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class SchedulerExtraInformation
 * @package Rancherize\Blueprint\Scheduler
 */
class SchedulerExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'scheduler';

	/**
	 * @var string
	 */
	protected $scheduler;

	/**
	 * @var bool
	 */
	protected $allowSameHost;

	/**
	 * @var array
	 */
	protected $requireTags = [];

	/**
	 * @var array
	 */
	protected $forbidTags = [];

	/**
	 * @var array
	 */
	protected $shouldHaveTags = [];

	/**
	 * @var array
	 */
	protected $shouldNotTags = [];

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return self::IDENTIFIER;
	}

	/**
	 * @return string
	 */
	public function getScheduler() {
		return $this->scheduler;
	}

	/**
	 * @param string $scheduler
	 */
	public function setScheduler( $scheduler ) {
		$this->scheduler = $scheduler;
	}

	/**
	 * @return bool
	 */
	public function isAllowSameHost() {
		return $this->allowSameHost;
	}

	/**
	 * @param bool $allowSameHost
	 */
	public function setAllowSameHost( $allowSameHost ) {
		$this->allowSameHost = $allowSameHost;
	}

	/**
	 * @return array
	 */
	public function getRequireTags() {
		return $this->requireTags;
	}

	/**
	 * @param array $requireTags
	 */
	public function setRequireTags( array $requireTags ) {
		$this->requireTags = $requireTags;
	}

	/**
	 * @return array
	 */
	public function getForbidTags() {
		return $this->forbidTags;
	}

	/**
	 * @param array $forbidTags
	 */
	public function setForbidTags( array $forbidTags ) {
		$this->forbidTags = $forbidTags;
	}

	/**
	 * @return array
	 */
	public function getShouldHaveTags(): array {
		return $this->shouldHaveTags;
	}

	/**
	 * @param array $shouldHaveTags
	 */
	public function setShouldHaveTags( array $shouldHaveTags ) {
		$this->shouldHaveTags = $shouldHaveTags;
	}

	/**
	 * @return array
	 */
	public function getShouldNotTags(): array {
		return $this->shouldNotTags;
	}

	/**
	 * @param array $shouldNotTags
	 */
	public function setShouldNotTags( array $shouldNotTags ) {
		$this->shouldNotTags = $shouldNotTags;
	}

}