<?php namespace Rancherize\InputOutput;

use Rancherize\InputOutput\Events\InputOutputToContainerEventHandler;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class InputOutputProvider
 * @package Rancherize\InputOutput
 */
class InputOutputProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[InputOutputToContainerEventHandler::class] = function($c) {
			return new InputOutputToContainerEventHandler($c);
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $eventDispatcher
		 */
		$eventDispatcher = $this->container['event'];
		$eventHandler = $this->container[InputOutputToContainerEventHandler::class];

		$eventDispatcher->addListener(ConsoleEvents::COMMAND, [$eventHandler, 'prepareCommand']);
	}
}