<?php namespace Rancherize\Blueprint\Cron;

use Illuminate\Console\Scheduling\Schedule;
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
		$this->container[CronInit::class] = function() {
			return new CronInit();
		};
		$this->container['cron-init'] = function($c) {
			return $c[CronInit::class];
		};

		$this->container[ScheduleParser::class] = function($c) {
			return new ScheduleParser();
		};

		$this->container['schedule-parser'] = function($c) {
			return $c[ScheduleParser::class];
		};

		$this->container[CronService::class] = function($c) {
			return new CronService();
		};

		$this->container['cron-service'] = function($c) {
			return $c[CronService::class];
		};


		$this->container[CronParser::class] = function($c) {
			return new CronParser( $c['schedule-parser'], $c['cron-service']);
		};

		$this->container['cron-parser'] = function($c) {
			return $c[CronParser::class];
		};
	}

	/**
	 */
	public function boot() {
	}
}