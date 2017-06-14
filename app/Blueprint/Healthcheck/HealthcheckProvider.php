<?php namespace Rancherize\Blueprint\Healthcheck;

use Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter\HealthcheckYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterRancherServicePreparedEvent;
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
		$this->container['healthcheck-yaml-writer'] = function() {
			return new HealthcheckYamlWriter();
		};
		$this->container[healthcheck-service-writer-listener] = function () {

		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = c['healthcheck-service-writer-listener'];
		$event->addListener(ServiceWriterRancherServicePreparedEvent::NAME, [$listener, 'rancherServicePrepared']);
	}
}