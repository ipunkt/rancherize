<?php namespace Rancherize\InputOutput\Events;

use Pimple\Container;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InputOutputToContainerEventHandler
 * @package Rancherize\InputOutput\Events
 */
class InputOutputToContainerEventHandler {
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * InputOutputToContainerEventHandler constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container) {
		$this->container = $container;
	}

	public function prepareCommand( ConsoleCommandEvent $commandEvent ) {

		$input = $commandEvent->getInput();
		$output = $commandEvent->getOutput();

		$this->container[InputInterface::class] = $input;
		$this->container[OutputInterface::class] = $output;

	}

}