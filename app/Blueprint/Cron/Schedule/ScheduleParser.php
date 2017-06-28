<?php namespace Rancherize\Blueprint\Cron\Schedule;

use Rancherize\Configuration\Configuration;

/**
 * Class ScheduleParser
 * @package Rancherize\Blueprint\Cron\Schedule
 */
class ScheduleParser {

	/**
	 * @param Configuration $configuration
	 * @return Schedule
	 */
	public function parseSchedule( Configuration $configuration ) {
		$schedule = new Schedule();

		$scheduleString = $configuration->get('schedule');

		return $schedule;
	}

}