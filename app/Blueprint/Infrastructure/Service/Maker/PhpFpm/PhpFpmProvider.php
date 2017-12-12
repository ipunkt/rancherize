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

		$this->container[PhpFpmMaker::class] = function() {
			$phpFpmMaker = new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker();

			return $phpFpmMaker;
		};

		$this->container[PHP70::class] = function() {
			return new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions\PHP70();
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