<?php namespace Rancherize\Blueprint\Cron\Schedule;

/**
 * Class Schedule
 * @package Rancherize\Blueprint\Cron\Schedule
 */
class Schedule {

	/**
	 * @var string
	 */
	protected $seconds = null;

	/**
	 * @var string
	 */
	protected $minute = null;

	/**
	 * @var string
	 */
	protected $hour = null;

	/**
	 * @var string
	 */
	protected $month = null;

	/**
	 * @var string
	 */
	protected $dayOfWeek = null;

	/**
	 * @var string
	 */
	protected $dayOfMonth = null;
	private $minutes;
	private $hours;

	/**
	 * Schedule constructor.
	 * @param $seconds
	 * @param $minutes
	 * @param $hours
	 * @param $dayOfMonth
	 * @param $month
	 * @param $dayOfWeek
	 */
	public function __construct( $seconds = null, $minutes = null, $hours = null, $dayOfMonth = null, $month = null, $dayOfWeek = null) {
		if($seconds === null)
			$seconds = '*';
		if($minutes === null)
			$minutes = '*';
		if($hours === null)
			$hours = '*';
		if($dayOfMonth === null)
			$dayOfMonth = '*';
		if($month === null)
			$month = '*';
		if($dayOfWeek === null)
			$dayOfWeek = '*';

		$this->seconds = $seconds;
		$this->minutes = $minutes;
		$this->hours = $hours;
		$this->dayOfMonth = $dayOfMonth;
		$this->month = $month;
		$this->dayOfWeek = $dayOfWeek;
	}

	/**
	 * @return string
	 */
	public function getMinute(): string {
		return $this->minute;
	}

	/**
	 * @param string $minute
	 */
	public function setMinute( string $minute ) {
		$this->minute = $minute;
	}

	/**
	 * @return string
	 */
	public function getHour(): string {
		return $this->hour;
	}

	/**
	 * @param string $hour
	 */
	public function setHour( string $hour ) {
		$this->hour = $hour;
	}
	/**
	 * @return string
	 */
	public function getMonth(): string {
		return $this->month;
	}

	/**
	 * @param string $month
	 */
	public function setMonth( string $month ) {
		$this->month = $month;
	}

	/**
	 * @return string
	 */
	public function getDayOfWeek(): string {
		return $this->dayOfWeek;
	}

	/**
	 * @param string $dayOfWeek
	 */
	public function setDayOfWeek( string $dayOfWeek ) {
		$this->dayOfWeek = $dayOfWeek;
	}

	/**
	 * @return string
	 */
	public function getDayOfMonth(): string {
		return $this->dayOfMonth;
	}

	/**
	 * @param string $dayOfMonth
	 */
	public function setDayOfMonth( string $dayOfMonth ) {
		$this->dayOfMonth = $dayOfMonth;
	}

	/**
	 * @return string
	 */
	public function getSeconds(): string {
		return $this->seconds;
	}

	/**
	 * @param string $seconds
	 */
	public function setSeconds( string $seconds ) {
		$this->seconds = $seconds;
	}

}