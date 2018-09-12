<?php namespace Rancherize\Blueprint\PhpCommands;

use Rancherize\Blueprint\Cron\CronService\CronService;
use Rancherize\Blueprint\Cron\Schedule\ScheduleParser;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker;
use Rancherize\Blueprint\PhpCommands\EventHandler\PhpCommandsEventHandler;
use Rancherize\Blueprint\PhpCommands\Parser\ArrayParser;
use Rancherize\Blueprint\PhpCommands\Parser\NameParser;
use Rancherize\Blueprint\PhpCommands\Parser\PhpCommandsParser;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class PhpCommandsProvider
 * @package Rancherize\Blueprint\PhpCommands
 */
class PhpCommandsProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[ArrayParser::class] = function () {
			return new ArrayParser();
		};

		$this->container[NameParser::class] = function () {
			return new NameParser();
		};

		$this->container[PhpCommandsParser::class] = function ( $c ) {
			return new PhpCommandsParser( $c[ArrayParser::class], $c[NameParser::class] );
		};

		$this->container[PhpCommandsEventHandler::class] = function( $c) {
			return new PhpCommandsEventHandler( $c[PhpFpmMaker::class], $c[PhpCommandsParser::class], $c[CronService::class], $c[ScheduleParser::class] );
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container[EventDispatcher::class];

		$eventHandler = $this->container[PhpCommandsEventHandler::class];

		$event->addListener(MainServiceBuiltEvent::NAME, [$eventHandler, 'mainServiceBuilt']);
		$event->addListener(PushCommandInServiceUpgradeEvent::NAME, [$eventHandler, 'inServiceUpgrade']);
		$event->addListener(PushCommandStartEvent::NAME, [$eventHandler, 'startService']);
	}
}