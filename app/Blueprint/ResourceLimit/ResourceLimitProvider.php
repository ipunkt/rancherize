<?php namespace Rancherize\Blueprint\ResourceLimit;

use Rancherize\Blueprint\Events\MainServiceBuiltEvent;
use Rancherize\Blueprint\Events\ServiceBuildEvent;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\ResourceLimit\EventListener\ServiceBuiltListener;
use Rancherize\Blueprint\ResourceLimit\EventListener\ServiceWriteListener;
use Rancherize\Blueprint\ResourceLimit\Parser\CpuLimitModeFactory;
use Rancherize\Blueprint\ResourceLimit\Parser\MemLimitModeFactory;
use Rancherize\Blueprint\ResourceLimit\Parser\MemModes\FullMemMode;
use Rancherize\Blueprint\ResourceLimit\Parser\MemModes\HighMemMode;
use Rancherize\Blueprint\ResourceLimit\Parser\MemModes\LowMemMode;
use Rancherize\Blueprint\ResourceLimit\Parser\MemModes\MinimalMemMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\FullCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\HighCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\LowCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Modes\MinimalCpuMode;
use Rancherize\Blueprint\ResourceLimit\Parser\Parser;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\RancherService;
use Rancherize\Services\UnitConversionService\UnitConversionService;
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

		$this->container[MinimalCpuMode::class] = function () {
			return new MinimalCpuMode();
		};
		$this->container['resource-limit.cpu-limit.minimal'] = function ( $c ) {
			return $c[MinimalCpuMode::class];
		};

		$this->container[CpuLimitModeFactory::class] = function ( $c ) {
			return new CpuLimitModeFactory( $c );
		};

		$this->container[FullMemMode::class] = function () {
			return new FullMemMode();
		};
		$this->container['resource-limit.mem-limit.full'] = function ( $c ) {
			return $c[FullMemMode::class];
		};
		$this->container[HighMemMode::class] = function () {
			return new HighMemMode();
		};
		$this->container['resource-limit.mem-limit.high'] = function ( $c ) {
			return $c[HighMemMode::class];
		};
		$this->container[LowMemMode::class] = function () {
			return new LowMemMode();
		};
		$this->container['resource-limit.mem-limit.low'] = function ( $c ) {
			return $c[LowMemMode::class];
		};
		$this->container[MinimalMemMode::class] = function () {
			return new MinimalMemMode();
		};
		$this->container['resource-limit.mem-limit.minimal'] = function ( $c ) {
			return $c[MinimalMemMode::class];
		};

		$this->container[MemLimitModeFactory::class] = function($c) {
			return new MemLimitModeFactory( $c );
		};

		$this->container[Parser::class] = function ( $c ) {
			return new Parser( $c[CpuLimitModeFactory::class], $c[MemLimitModeFactory::class] );
		};

		$this->container[ServiceWriteListener::class] = function ($c) {
			return new ServiceWriteListener( $c[RancherService::class], $c[UnitConversionService::class] );
		};
		$this->container[ServiceBuiltListener::class] = function ( $c ) {
			return new ServiceBuiltListener( $c[Parser::class] );
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
		$event->addListener( ServiceWriterServicePreparedEvent::NAME, [$serviceWriteListener, 'writeService'] );
		$serviceBuiltListener = $this->container[ServiceBuiltListener::class];
		$event->addListener( MainServiceBuiltEvent::NAME, [$serviceBuiltListener, 'mainServiceBuilt'] );
        $event->addListener( ServiceBuildEvent::NAME, [$serviceBuiltListener, 'serviceBuilt'] );
	}
}