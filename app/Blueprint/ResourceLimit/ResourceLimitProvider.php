<?php namespace Rancherize\Blueprint\ResourceLimit;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\ResourceLimit\EventListener\MainServiceBuiltListener;
use Rancherize\Blueprint\ResourceLimit\EventListener\ServiceWriteListener;
use Rancherize\Blueprint\ResourceLimit\Parser\CpuLimitModeFactory;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\FullCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\HighCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\LowCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Parser;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ResourceLimitProvider
 * @package Rancherize\Blueprint\ResourceLimit
 */
class ResourceLimitProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[FullCpuMode::class] = function () {
			return new FullCpuMode();
		};
		$this->container['resource-limit.cpu-limit.full'] = function ( $c ) {
			return $c[FullCpuMode::class];
		};

		$this->container[HighCpuMode::class] = function () {
			return new HighCpuMode();
		};
		$this->container['resource-limit.cpu-limit.high'] = function ( $c ) {
			return $c[HighCpuMode::class];
		};

		$this->container[LowCpuMode::class] = function () {
			return new LowCpuMode();
		};
		$this->container['resource-limit.cpu-limit.low'] = function ( $c ) {
			return $c[LowCpuMode::class];
		};

		$this->container[CpuLimitModeFactory::class] = function ( $c ) {
			return new CpuLimitModeFactory( $c );
		};

		$this->container[Parser::class] = function ( $c ) {
			return new Parser( $c[CpuLimitModeFactory::class] );
		};

		$this->container[ServiceWriteListener::class] = function () {
			return new ServiceWriteListener();
		};
		$this->container[MainServiceBuiltListener::class] = function ( $c ) {
			return new MainServiceBuiltListener( $c[Parser::class] );
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];

		$serviceWriteListener = $this->container[ServiceWriteListener::class];
		$event->addListener( ServiceWriterServicePreparedEvent::NAME, [$serviceWriteListener, 'writeServices'] );
		$mainServiceBuiltListener = $this->container[MainServiceBuiltListener::class];
		$event->addListener( MainServiceBuiltEvent::NAME, [$mainServiceBuiltListener, 'mainServiceBuilt'] );
	}
}