<?php namespace Rancherize\Blueprint\ResourceLimit\EventListener;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Events\ServiceBuiltEvent;
use Rancherize\Blueprint\Events\SidekickBuiltEvent;
use Rancherize\Blueprint\ResourceLimit\Parser\Parser;

/**
 * Class MainServiceBuiltListener
 * @package Rancherize\Blueprint\ResourceLimit\EventListener
 */
class ServiceBuiltListener {
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

		$mainService = $event->getMainService();
		$this->parser->parse( $mainService, $event->getEnvironmentConfiguration() );

		foreach($mainService->getSidekicks() as $sidekick)
			$this->parser->parseLimit( $sidekick, $event->getEnvironmentConfiguration() );

	}

    /**
     * @param ServiceBuiltEvent $event
     */
    public function serviceBuilt(ServiceBuiltEvent $event) {

	    $service = $event->getService();

	    $config = $event->getConfiguration();

	    $this->parser->parse( $service, $config );
	    foreach( $service->getSidekicks() as $sidekick )
            $this->parser->parseLimit( $sidekick, $config );
	}

    /**
     * This allows overriding the limits inherited by the main service
     *
     * @param SidekickBuiltEvent $event
     */
    public function sidekickBuilt(SidekickBuiltEvent $event) {

        $service = $event->getService();

        $config = $event->getConfiguration();

        $this->parser->parseLimit( $service, $config );
    }
}