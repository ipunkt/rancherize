<?php namespace Rancherize\Commands\Events;

use Rancherize\Configuration\Configurable;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InitCommandEvent
 * @package Rancherize\Commands\Events
 */
class InitCommandEvent extends Event {

	const NAME = 'command.init';
	/**
	 * @var Configurable
	 */
	private $configuration;

	/**
	 * InitCommandEvent constructor.
	 * @param Configurable $configuration
	 */
	public function __construct( Configurable $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * @return Configurable
	 */
	public function getConfiguration(): Configurable {
		return $this->configuration;
	}

}