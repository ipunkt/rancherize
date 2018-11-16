<?php namespace Rancherize\Blueprint\DataImages\EventListener;

use Rancherize\Blueprint\DataImages\Parser\Parser;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;

/**
 * Class EventListener
 * @package Rancherize\Blueprint\DataImages\EventListener
 */
class EventListener
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * EventListener constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param MainServiceBuiltEvent $event
     */
    public function mainServiceBuilt(MainServiceBuiltEvent $event)
    {
        $this->parser->parse($event->getEnvironmentConfiguration(), $event->getMainService(),
            $event->getInfrastructure());
    }

}