<?php namespace Rancherize\Blueprint\Cron\EventListener;

use Rancherize\Blueprint\Cron\CronParser\CronParser;
use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\Exceptions\NoScheduleConfiguredException;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Events\ServiceBuiltEvent;

/**
 * Class ServiceBuiltListener
 * @package Rancherize\Blueprint\Cron\EventListener
 */
class ServiceBuiltListener
{
    /**
     * @var CronParser
     */
    private $parser;
    /**
     * @var CronService
     */
    private $cronService;
    /**
     * @var ScheduleParser
     */
    private $scheduleParser;

    /**
     * ServiceBuiltListener constructor.
     * @param ScheduleParser $scheduleParser
     * @param CronService $cronService
     */
    public function __construct(ScheduleParser $scheduleParser, CronService $cronService)
    {
        $this->cronService = $cronService;
        $this->scheduleParser = $scheduleParser;
    }

    public function serviceBuilt(ServiceBuiltEvent $event)
    {
        $service = $event->getService();

        $config = $event->getConfiguration();

        try {
            $schedule = $this->scheduleParser->parseSchedule($config);
            $this->cronService->makeCron($service, $schedule);
        } catch (NoScheduleConfiguredException $e) {
            // no schedule, don't make it into a cronjob
        }

    }

}