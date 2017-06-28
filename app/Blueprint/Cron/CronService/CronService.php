<?php namespace Rancherize\Blueprint\Cron\CronService;

use Rancherize\Blueprint\Cron\Schedule\Schedule;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class CronService
 * @package Rancherize\Blueprint\Cron\CronService
 */
class CronService {

	/**
	 * @param Service $service
	 * @param Schedule $schedule
	 */
	public function makeCron( Service $service, Schedule $schedule ) {
		$labels = $service->getLabels();

		$seconds = $schedule->getSeconds();
		$minutes = $schedule->getMinute();
		$hours = $schedule->getHour();
		$dayOfMonth = $schedule->getDayOfMonth();
		$month = $schedule->getMonth();
		$dayOfWeek = $schedule->getDayOfWeek();

		$labels['cron.schedule'] = "$seconds $minutes $hours $dayOfMonth $month $dayOfWeek";
	}

}