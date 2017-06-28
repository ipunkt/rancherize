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
		if( is_string($scheduleString) ) {
			$parts = explode(' ', $scheduleString);

			$schedule->setSeconds( $parts[0] );
			$schedule->setMinute( $parts[1] );
			$schedule->setHour( $parts[2] );
			$schedule->setMonth( $parts[3] );
			$schedule->setDayOfMonth( $parts[4] );
			$schedule->setDayOfWeek( $parts[5] );

			return $schedule;
		}

		$schedule->setHour( $configuration->get('hour', '*') );
		$schedule->setMinute( $configuration->get('minute', '*') );
		$schedule->setSeconds( $configuration->get('seconds', '*') );
		$schedule->setMonth( $configuration->get('hour', '*') );
		$schedule->setDayOfMonth( $configuration->get('dayOfMonth', '*') );
		$schedule->setDayOfWeek( $configuration->get('dayOfWeek', '*') );

		return $schedule;
	}

}