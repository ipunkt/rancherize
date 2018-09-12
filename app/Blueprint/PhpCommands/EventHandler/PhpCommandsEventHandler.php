<?php namespace Rancherize\Blueprint\PhpCommands\EventHandler;

use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\Exceptions\NoScheduleConfiguredException;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\PhpCommands\Parser\PhpCommandsParser;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;

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
	 * @var Service[]
	 */
	protected $builtServices = [];

	/**
	 * @param MainServiceBuiltEvent $event
	 */
	public function mainServiceBuilt( MainServiceBuiltEvent $event ) {

		$infrastructure = $event->getInfrastructure();

		$mainService = $event->getMainService();

		$config = $event->getEnvironmentConfiguration();

		$commands = $this->commandsParser->parse( $config );
		foreach ( $commands as $command ) {
			if( $command->isService() ) {
				$service = $this->fpmMaker->makeService( $command->getName(), $command->getCommand(), $mainService, $config );
				$this->builtServices[] = $service;
			} else
				$service = $this->fpmMaker->makeCommand( $command->getName(), $command->getCommand(), $mainService, $config );

			$restart = [
				'never' => Service::RESTART_NEVER,
				'always' => Service::RESTART_ALWAYS,
				'unless-stopped' => Service::RESTART_UNLESS_STOPPED,
				'start-once' => Service::RESTART_START_ONCE,
			];

			if ( array_key_exists( $command->getRestart(), $restart ) )
				$service->setRestart( $restart[$command->getRestart()] );

			try {
				$schedule = $this->scheduleParser->parseSchedule( $command->getConfiguration() );
				$this->cronService->makeCron( $service, $schedule );
			} catch ( NoScheduleConfiguredException $e ) {
				// do nothing, no schedule configurated
			}

			$infrastructure->addService( $service );
		}

	}

	/**
	 * @param PushCommandInServiceUpgradeEvent $event
	 */
	public function inServiceUpgrade( PushCommandInServiceUpgradeEvent $event ) {

		$serviceNames = $event->getServiceNames();

		foreach($this->builtServices as $service)
			$serviceNames[] = $service->getName();

		$event->setServiceNames($serviceNames);
	}


	/**
	 * @param PushCommandInServiceUpgradeEvent $event
	 */
	public function startService( PushCommandStartEvent $event ) {

		$serviceNames = $event->getServiceNames();

		foreach($this->builtServices as $service)
			$serviceNames[] = $service->getName();

		$event->setServiceNames($serviceNames);
	}
}