<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

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

		/**
		 * Service Maker
		 */
		$this->container['php-fpm-maker'] = function($c) {
			$phpFpmMaker = new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker();


			return $phpFpmMaker;
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var PhpFpmMaker $fpmMaker
		 */
		$fpmMaker = $this->container['php-fpm-maker'];

		$fpmMaker->addVersion(new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions\PHP70());

	}
}