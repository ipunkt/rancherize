<?php namespace Rancherize\Blueprint\PhpCommands\EventHandler;

use Rancherize\Blueprint\Cron\CronParser\CronParser;
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
	 * @var CronParser
	 */
	private $cronParser;

	/**
	 * PhpCommandsEventHandler constructor.
	 * @param PhpFpmMaker $fpmMaker
	 * @param PhpCommandsParser $commandsParser
	 * @param CronParser $cronParser
	 */
	public function __construct( PhpFpmMaker $fpmMaker, PhpCommandsParser $commandsParser, CronParser $cronParser ) {
		$this->fpmMaker = $fpmMaker;
		$this->commandsParser = $commandsParser;
		$this->cronParser = $cronParser;
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

			$infrastructure->addService($service);
		}

	}

}