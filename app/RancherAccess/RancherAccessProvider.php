<?php namespace Rancherize\RancherAccess;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\ApiService\ApiService;
use Rancherize\RancherAccess\ApiService\CurlApiService;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;
use Rancherize\RancherAccess\UpgradeMode\RollingUpgradeChecker;
use Rancherize\RancherAccess\UpgradeMode\UpgradeModeFromConfiguration;

/**
 * Class DockerProvider
 * @package Rancherize\Docker
 */
class RancherAccessProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[ApiService::class] = function() {
			return new CurlApiService();
		};

		$this->container[RancherAccessService::class] = function() {
			return new RancherAccessConfigService();
		};

		$this->container[RancherService::class] = function($c) {
			return new RancherService( $c[ApiService::class] );
		};

		$this->container[UpgradeModeFromConfiguration::class] = function() {
			return new UpgradeModeFromConfiguration();
		};

		$this->container[InServiceChecker::class] = function($c) {
			return new InServiceChecker( $c[UpgradeModeFromConfiguration::class] );
		};

		$this->container[ReplaceUpgradeChecker::class] = function($c) {
			return new ReplaceUpgradeChecker( $c[UpgradeModeFromConfiguration::class] );
		};

		$this->container[RollingUpgradeChecker::class] = function($c) {
			return new RollingUpgradeChecker( $c[InServiceChecker::class], $c[ReplaceUpgradeChecker::class] );
		};

	}

	/**
	 */
	public function boot() {
	}
}