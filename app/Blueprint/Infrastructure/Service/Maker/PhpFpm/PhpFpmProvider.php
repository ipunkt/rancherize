<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions\PHP70;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class PhpFpmProvider
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
class PhpFpmProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {

		$this->container[AlpineDebugImageBuilder::class] = function() {
			return new AlpineDebugImageBuilder();
		};

		$this->container[PhpFpmMaker::class] = function() {
			$phpFpmMaker = new PhpFpmMaker();

			return $phpFpmMaker;
		};

		$this->container[PHP70::class] = function($c) {
			return new PHP70(
				$c[AlpineDebugImageBuilder::class]
			);
		};

		/**
		 * Service Maker
		 */
		$this->container['php-fpm-maker'] = function($c) {
			return $c[PhpFpmMaker::class];
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var PhpFpmMaker $fpmMaker
		 */
		$fpmMaker = $this->container[PhpFpmMaker::class];

		$fpmMaker->addVersion( $this->container[PHP70::class] );

	}
}