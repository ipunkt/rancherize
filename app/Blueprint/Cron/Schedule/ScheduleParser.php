<?php namespace Rancherize\Blueprint\Cron\Schedule;

use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

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

		$scheduleConfiguration = new PrefixConfigurationDecorator($configuration, 'schedule.');
		$schedule->setHour( $scheduleConfiguration->get('hour', '*') );
		$schedule->setMinute( $scheduleConfiguration->get('minute', '*') );
		$schedule->setSeconds( $scheduleConfiguration->get('seconds', '*') );
		$schedule->setMonth( $scheduleConfiguration->get('hour', '*') );
		$schedule->setDayOfMonth( $scheduleConfiguration->get('dayOfMonth', '*') );
		$schedule->setDayOfWeek( $scheduleConfiguration->get('dayOfWeek', '*') );

		return $schedule;
	}

}