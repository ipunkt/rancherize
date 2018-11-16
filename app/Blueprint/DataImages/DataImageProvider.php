<?php namespace Rancherize\Blueprint\DataImages;

use Rancherize\Blueprint\DataImages\EventListener\EventListener;
use Rancherize\Blueprint\DataImages\Parser\Parser;
use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Docker\NameCleaner\NameCleaner;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class DataImageProvider
 * @package Rancherize\Blueprint\DataImages
 */
class DataImageProvider implements Provider
{
    use ProviderTrait;

    /**
     */
    public function register()
    {
        $this->container[Parser::class] = function ($c) {
            return new Parser($c[NameCleaner::class]);
        };

        $this->container[EventListener::class] = function ($c) {
            return new EventListener($c[Parser::class]);
        };
    }

    /**
     */
    public function boot()
    {
        /**
         * @var EventListener $listener
         */
        $listener = $this->container[EventListener::class];

        /**
         * @var EventDispatcher $event
         */
        $event = $this->container[EventDispatcher::class];
        $event->addListener(MainServiceBuiltEvent::NAME, [$listener, 'mainServiceBuilt']);
    }
}