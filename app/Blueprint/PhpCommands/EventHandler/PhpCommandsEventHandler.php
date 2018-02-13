<?php namespace Rancherize\Blueprint\PhpCommands\EventHandler;

use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\Exceptions\NoScheduleConfiguredException;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker;
use Rancherize\Blueprint\PhpCommands\Parser\PhpCommandsParser;

/**
 * Class PhpCommandsEventHandler
 * @package Rancherize\Blueprint\PhpCommands\EventHandler
 */
class PhpCommandsEventHandler {
	/**
	 * @var PhpFpmMaker
	 */
	private $fpmMaker;
	/**
	 * @var PhpCommandsParser
	 */
	private $commandsParser;
	/**
	 * @var ScheduleParser
	 */
	private $scheduleParser;
	/**
	 * @var CronService
	 */
	private $cronService;

	/**
	 * PhpCommandsEventHandler constructor.
	 * @param PhpFpmMaker $fpmMaker
	 * @param PhpCommandsParser $commandsParser
	 * @param CronService $cronService
	 * @param ScheduleParser $scheduleParser
	 */
	public function __construct( PhpFpmMaker $fpmMaker, PhpCommandsParser $commandsParser, CronService $cronService, ScheduleParser $scheduleParser ) {
		$this->fpmMaker = $fpmMaker;
		$this->commandsParser = $commandsParser;
		$this->scheduleParser = $scheduleParser;
		$this->cronService = $cronService;
	}

	/**
	 * @param MainServiceBuiltEvent $event
	 */
	public function mainServiceBuilt( MainServiceBuiltEvent $event ) {

		$infrastructure = $event->getInfrastructure();

		$mainService = $event->getMainService();

		$config = $event->getEnvironmentConfiguration();

		$commands = $this->commandsParser->parse($config);
		foreach( $commands as $command) {
			$service = $this->fpmMaker->makeCommand( $command->getName(), $command->getCommand(), $mainService, $config );

			try {
				$schedule = $this->scheduleParser->parseSchedule( $command->getConfiguration() );
				$this->cronService->makeCron( $service, $schedule );
			} catch ( NoScheduleConfiguredException $e ) {
				// do nothing, no schedule configurated
			}

			$infrastructure->addService($service);
		}

	}

}