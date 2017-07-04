<?php namespace Rancherize\Blueprint\Cron\CronParser;

use Closure;
use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class CronParser
 * @package Rancherize\Blueprint\Cron\CronParser
 */
class CronParser {
	/**
	 * @var ScheduleParser
	 */
	private $scheduleParser;
	/**
	 * @var CronService
	 */
	private $cronService;

	/**
	 * CronParser constructor.
	 * @param ScheduleParser $scheduleParser
	 * @param CronService $cronService
	 */
	public function __construct( ScheduleParser $scheduleParser, CronService $cronService ) {
		$this->scheduleParser = $scheduleParser;
		$this->cronService = $cronService;
	}

	/**
	 * @param Configuration $config
	 * @param Infrastructure $infrastructure
	 * @param Closure|null $newService
	 */
	public function parse(Configuration $config, Infrastructure $infrastructure, Closure $newService = null) {
		if($newService === null) {
			$newService = function($name, $command) {
				$service = new Service();

				$service->setName($name);
				$service->setCommand($command);

				return $service;
			};
		}

		if( !$config->get('cron.enable', true) )
			return;

		$cronjobs = $config->get('cron', []);


		if( !is_array($cronjobs) )
			$cronjobs = [];
		if(array_key_exists('enable', $cronjobs))
			unset($cronjobs['enable']);

		$cronIndex = 1;
		foreach ($cronjobs as $name => $cronjob) {
			$serviceConfig = new PrefixConfigurationDecorator($config, 'cron.'.$name.'.');

			if( is_numeric($name) )
				$name = 'cron-'.$cronIndex++;

			$command = $serviceConfig->get('command');
			$schedule = $this->scheduleParser->parseSchedule($serviceConfig);

			/**
			 * @var Service $service
			 */
			$service = $newService($name, $command);
			$this->cronService->makeCron($service, $schedule);

			$infrastructure->addService( $service );
		}
	}
}