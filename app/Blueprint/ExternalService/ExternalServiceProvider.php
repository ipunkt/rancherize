<?php namespace Rancherize\Blueprint\ExternalService;

use Rancherize\Blueprint\ExternalService\EventListener\ExternalServiceEventListener;
use Rancherize\Blueprint\ExternalService\ExternalServiceParser\ExternalServiceParser;
use Rancherize\Blueprint\ExternalService\ExternalServiceYamlWriter\ExternalServiceYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ExternalServiceProvider
 * @package Rancherize\Blueprint\ExternalService
 */
class ExternalServiceProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		/**
		 * @return ExternalServiceParser
		 */
		$this->container['external-service-parser'] = function() {
			return new ExternalServiceParser();
		};

		$this->container['external-service-yaml-writer'] = function() {
			return new ExternalServiceYamlWriter();
		};

		$this->container['external-service-service-writer-listener'] = function($c) {
			return new ExternalServiceEventListener( $c['external-service-yaml-writer'] );
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['external-service-service-writer-listener'];
		$event->addListener(ServiceWriterServicePreparedEvent::NAME, [$listener, 'servicePrepared']);
	}
}