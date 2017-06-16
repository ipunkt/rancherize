<?php namespace Rancherize\Blueprint\Healthcheck;

use Rancherize\Blueprint\Healthcheck\EventListener\HealthcheckServiceWriterListener;
use Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService\HealthcheckConfigurationToService;
use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckDefaultInformationSetter;
use Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter\HealthcheckYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
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
		$this->container['healthcheck-service-writer-listener'] = function ($c) {
			return new HealthcheckServiceWriterListener( $c['healthcheck-yaml-writer'] );
		};
		$this->container['healthcheck-default-setter'] = function() {
			return new HealthcheckDefaultInformationSetter;
		};
		$this->container['healthcheck-parser'] = function ($c) {
			return new HealthcheckConfigurationToService( $c['healthcheck-default-setter'] );
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['healthcheck-service-writer-listener'];
		$event->addListener(ServiceWriterServicePreparedEvent::NAME, [$listener, 'servicePrepared']);
	}
}