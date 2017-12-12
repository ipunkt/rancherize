<?php namespace Rancherize\Blueprint\PhpCommands;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker;
use Rancherize\Blueprint\PhpCommands\EventHandler\PhpCommandsEventHandler;
use Rancherize\Blueprint\PhpCommands\Parser\PhpCommandsParser;
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
		$this->container[PhpCommandsParser::class] = function() {
			return new PhpCommandsParser();
		};

		$this->container[PhpCommandsEventHandler::class] = function($c) {
			return new PhpCommandsEventHandler( $c[PhpFpmMaker::class], $c[PhpCommandsParser::class] );
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
	}
}