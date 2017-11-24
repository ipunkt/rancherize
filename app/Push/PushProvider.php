<?php namespace Rancherize\Push;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Push\ModeFactory\ArrayPushModeFactory;
use Rancherize\Push\ModeFactory\PushModeFactory;
use Rancherize\Push\Modes\InServiceUpgrade\InServiceModeParser;
use Rancherize\Push\Modes\InServiceUpgrade\InServicePushMode;
use Rancherize\Push\Modes\ReplaceUpgrade\ReplacePushMode;
use Rancherize\Push\Modes\ReplaceUpgrade\ReplaceUpgradeParser;
use Rancherize\Push\Modes\RollingUpgrade\RollingPushMode;
use Rancherize\Push\Modes\RollingUpgrade\RollingUpgradeParser;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;

/**
 * Class PushProvider
 * @package Rancherize\Push
 */
class PushProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[PushModeFactory::class] = function() {
			return new ArrayPushModeFactory();
		};

		$this->container[InServicePushMode::class] = function($c) {
			return new InServicePushMode($c['event']);
		};

		$this->container[InServiceModeParser::class] = function($c) {
			return new InServiceModeParser($c[InServiceChecker::class]);
		};

		$this->container[RollingPushMode::class] = function() {
			return new RollingPushMode();
		};

		$this->container[RollingUpgradeParser::class] = function($c) {
			return new RollingUpgradeParser($c[InServiceChecker::class], $c[ReplaceUpgradeChecker::class]);
		};

		$this->container[ReplacePushMode::class] = function() {
			return new ReplacePushMode();
		};

		$this->container[ReplaceUpgradeParser::class] = function($c) {
			return new ReplaceUpgradeParser($c[ReplaceUpgradeChecker::class]);
		};

	}

	/**
	 */
	public function boot() {
		/**
		 * @var PushModeFactory $modeFactory
		 */
		$modeFactory = $this->container[PushModeFactory::class];

		$modeFactory->register($this->container[InServiceModeParser::class], $this->container[InServicePushMode::class] );
		$modeFactory->register($this->container[ReplaceUpgradeParser::class], $this->container[ReplacePushMode::class] );
		$modeFactory->register($this->container[RollingUpgradeParser::class], $this->container[RollingPushMode::class] );
	}
}