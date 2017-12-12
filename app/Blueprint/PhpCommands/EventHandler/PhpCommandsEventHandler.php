<?php namespace Rancherize\Blueprint\PhpCommands\EventHandler;

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
	 * PhpCommandsEventHandler constructor.
	 * @param PhpFpmMaker $fpmMaker
	 * @param PhpCommandsParser $commandsParser
	 */
	public function __construct( PhpFpmMaker $fpmMaker, PhpCommandsParser $commandsParser) {
		$this->fpmMaker = $fpmMaker;
		$this->commandsParser = $commandsParser;
	}

	/**
	 * @param MainServiceBuiltEvent $event
	 */
	public function mainServiceBuilt( MainServiceBuiltEvent $event ) {

		$infrastructure = $event->getInfrastructure();

		$mainService = $event->getMainService();

		$config = $event->getEnvironmentConfiguration();

		$commands = $this->commandsParser->parse($config);
		foreach($commands as $command) {
			$service = $this->fpmMaker->makeCommand( $command->getName(), $command->getCommand(), $mainService, $config );

			$infrastructure->addService($service);
		}

	}

}