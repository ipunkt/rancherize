<?php namespace Rancherize\Blueprint\Infrastructure\Service\Listeners;

use Pimple\Container;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Events\EnvironmentConfigurationLoadedEvent;

/**
 * Class AlwaysPullDefaultFromConfigurationListener
 * @package Rancherize\Blueprint\Infrastructure\Service\Listeners
 */
class AlwaysPullDefaultFromConfigurationListener {
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * AlwaysPullDefaultFromConfigurationListener constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * @param EnvironmentConfigurationLoadedEvent $event
	 */
	public function environmentConfigurationLoaded( EnvironmentConfigurationLoadedEvent $event ) {

		$environmentConfiguration = $event->getEnvironmentConfiguration();

		if ( $environmentConfiguration->get( 'docker.always-pull', true ) )
			$this->container['always-pulled-default'] = Service::ALWAYS_PULLED_TRUE;
		else
			$this->container['always-pulled-default'] = Service::ALWAYS_PULLED_FALSE;
	}
}