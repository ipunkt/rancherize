<?php namespace Rancherize\Blueprint\Cron;

use Illuminate\Console\Scheduling\Schedule;
use Rancherize\Blueprint\Cron\CronInit\CronInit;
use Rancherize\Blueprint\Cron\CronParser\CronParser;
use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\EventListener\ServiceBuiltListener;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Events\ServiceBuiltEvent;
use Rancherize\Blueprint\Events\SidekickBuiltEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class CronProvider
 * @package Rancherize\Blueprint\Cron
 */
class CronProvider implements Provider
{

    use ProviderTrait;

    /**
     */
    public function register()
    {
        $this->container[CronInit::class] = function () {
            return new CronInit();
        };
        $this->container['cron-init'] = function ($c) {
            return $c[CronInit::class];
        };

        $this->container[ScheduleParser::class] = function ($c) {
            return new ScheduleParser();
        };

        $this->container['schedule-parser'] = function ($c) {
            return $c[ScheduleParser::class];
        };

        $this->container[CronService::class] = function ($c) {
            return new CronService();
        };

        $this->container['cron-service'] = function ($c) {
            return $c[CronService::class];
        };


        $this->container[CronParser::class] = function ($c) {
            return new CronParser($c['schedule-parser'], $c['cron-service']);
        };

        $this->container['cron-parser'] = function ($c) {
            return $c[CronParser::class];
        };

        $this->container[EventListener\ServiceBuiltListener::class] = function ($c) {
            return new ServiceBuiltListener($c[ScheduleParser::class], $c[CronService::class]);
        };
    }

    /**
     */
    public function boot()
    {
        /**
         * @var EventDispatcher $event
         */
        $event = $this->container[EventDispatcher::class];

        $serviceBuiltListener = $this->container[ServiceBuiltListener::class];

        $event->addListener(ServiceBuiltEvent::NAME, [$serviceBuiltListener, 'serviceBuilt']);
        $event->addListener(SidekickBuiltEvent::NAME, [$serviceBuiltListener, 'sidekickBuilt']);
    }
}