<?php namespace Rancherize\Blueprint\Infrastructure;

use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Network\NetworkWriter;
use Rancherize\Blueprint\Infrastructure\Service\Listeners\AlwaysPullDefaultFromConfigurationListener;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\ServiceWriter;
use Rancherize\Blueprint\Infrastructure\Volume\VolumeWriter;
use Rancherize\Commands\Types\LocalCommand;
use Rancherize\Commands\Types\RancherCommand;
use Rancherize\Configuration\Events\EnvironmentConfigurationLoadedEvent;
use Rancherize\File\FileLoader;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class InfrastructureProvider
 * @package Rancherize\Blueprint\Infrastructure
 */
class InfrastructureProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[DockerfileWriter::class] = function() {
			return new DockerfileWriter();
		};
		$this->container['dockerfile-writer'] = function($c) {
			return $c[DockerfileWriter::class];
		};

		$this->container[ServiceWriter::class] = function($c) {
			return new ServiceWriter($c[FileLoader::class], $c['event'], $c[DockerfileWriter::class]);
		};
		$this->container['service-writer'] = function($c) {
			return $c[ServiceWriter::class];
		};

		$this->container[VolumeWriter::class] = function($c) {
			return new VolumeWriter($c[FileLoader::class]);
		};
		$this->container['volume-writer'] = function($c) {
			return $c[VolumeWriter::class];
		};

		$this->container[NetworkWriter::class] = function($c) {
			return new NetworkWriter( $c[FileLoader::class] );
		};

		$this->container[\Rancherize\Blueprint\Infrastructure\InfrastructureWriter::class] = function($c) {
			return new \Rancherize\Blueprint\Infrastructure\InfrastructureWriter(
				$c[DockerfileWriter::class],
				$c[ServiceWriter::class],
				$c[VolumeWriter::class],
				$c[NetworkWriter::class]);
		};

		$this->container['infrastructure-writer'] = function($c) {
			return $c[\Rancherize\Blueprint\Infrastructure\InfrastructureWriter::class];
		};

		$this->container[AlwaysPullDefaultFromConfigurationListener::class] = function ( $c ) {
			return new AlwaysPullDefaultFromConfigurationListener( $c );
		};

		$this->container['shared-network-mode'] = 'container:';
		$this->container['always-pulled-default'] = Service::ALWAYS_PULLED_TRUE;

	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $dispatcher
		 */
		$dispatcher = container('event');
		$dispatcher->addListener(ConsoleEvents::COMMAND, function( ConsoleCommandEvent $event ) {

			$command = $event->getCommand();
			if( $command instanceof LocalCommand ) {
				$container = container();
				$container['shared-network-mode'] = 'service:';

				return;
			}

			if( $command instanceof RancherCommand ) {
				// shared-network-mode defaults to 'container:' in the register function
				return;
			}
		});

		$listener = $this->container[AlwaysPullDefaultFromConfigurationListener::class];
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$event->addListener( EnvironmentConfigurationLoadedEvent::NAME, [$listener, 'environmentConfigurationLoaded'] );
	}
}