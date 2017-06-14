<?php namespace Rancherize\Blueprint\Healthcheck;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class HealthcheckProvider
 * @package Rancherize\Blueprint\Healthcheck
 */
class HealthcheckProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
	}
}