<?php namespace Rancherize\Blueprint\Cron;

use Rancherize\Blueprint\Cron\CronInit\CronInit;
use Rancherize\Blueprint\Cron\CronParser\CronParser;
use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class CronProvider
 * @package Rancherize\Blueprint\Cron
 */
class CronProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['cron-init'] = function() {
			return new CronInit();
		};

		$this->container['schedule-parser'] = function($c) {
			return new ScheduleParser();
		};

		$this->container['cron-service'] = function($c) {
			return new CronService();
		};

		$this->container['cron-parser'] = function($c) {
			return new CronParser( $c['schedule-parser'], $c['cron-service']);
		};
	}

	/**
	 */
	public function boot() {
	}
}