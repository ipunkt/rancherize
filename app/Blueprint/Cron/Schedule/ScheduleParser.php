<?php namespace Rancherize\Blueprint\Cron\Schedule;

use Rancherize\Blueprint\Cron\Schedule\Exceptions\NoScheduleConfiguredException;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class ScheduleParser
 * @package Rancherize\Blueprint\Cron\Schedule
 */
class ScheduleParser {

	public function arrayGet( $array, $key, $defaultValue ) {

		if(!array_key_exists($key, $array))
			return $defaultValue;

		return $array[$key];
	}

	/**
	 * @param Configuration $configuration
	 * @return Schedule
	 */
	public function parseSchedule( Configuration $configuration ) {
		$schedule = new Schedule();

		$scheduleKey = 'schedule';
		if( !$configuration->has($scheduleKey) )
			throw new NoScheduleConfiguredException();

		$scheduleString = $configuration->get( $scheduleKey );
		if( is_string($scheduleString) ) {
			$parts = explode(' ', $scheduleString);

			$schedule->setSeconds( $this->arrayGet($parts, 0, '*') );
			$schedule->setMinute( $this->arrayGet($parts, 1, '*') );
			$schedule->setHour( $this->arrayGet($parts, 2, '*') );
			$schedule->setMonth( $this->arrayGet($parts, 3, '*') );
			$schedule->setDayOfMonth( $this->arrayGet($parts, 4, '*') );
			$schedule->setDayOfWeek( $this->arrayGet($parts, 5, '*') );

			return $schedule;
		}

		$scheduleConfiguration = new PrefixConfigurationDecorator($configuration, 'schedule.');
		$schedule->setHour( $scheduleConfiguration->get('hour', '*') );
		$schedule->setMinute( $scheduleConfiguration->get('minute', '*') );
		$schedule->setSeconds( $scheduleConfiguration->get('second', '*') );
		$schedule->setMonth( $scheduleConfiguration->get('month', '*') );
		$schedule->setDayOfMonth( $scheduleConfiguration->get('dayOfMonth', '*') );
		$schedule->setDayOfWeek( $scheduleConfiguration->get('dayOfWeek', '*') );

		return $schedule;
	}

}