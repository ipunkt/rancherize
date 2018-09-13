<?php namespace Rancherize\Blueprint\ResourceLimit\EventListener;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\ResourceLimit\Parser\Parser;

/**
 * Class MainServiceBuiltListener
 * @package Rancherize\Blueprint\ResourceLimit\EventListener
 */
class MainServiceBuiltListener {
	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * MainServiceBuiltListener constructor.
	 * @param Parser $parser
	 */
	public function __construct( Parser $parser ) {
		$this->parser = $parser;
	}

	/**
	 * @param MainServiceBuiltEvent $event
	 */
	public function mainserviceBuilt( MainServiceBuiltEvent $event ) {

		$this->parser->parse( $event->getMainService(), $event->getEnvironmentConfiguration() );

	}
}