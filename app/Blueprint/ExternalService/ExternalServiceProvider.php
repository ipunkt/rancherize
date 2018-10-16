<?php namespace Rancherize\Blueprint\ExternalService;

use Rancherize\Blueprint\ExternalService\EventListener\ExternalServiceEventListener;
use Rancherize\Blueprint\ExternalService\EventListener\ExternalServicePushListener;
use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\ContainerExternalServiceFactory;
use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\RancherExternalServiceBuilder\RancherExternalServiceBuilder;
use Rancherize\Blueprint\ExternalService\ExternalServiceBuilder\TcpProxyExternalServiceBuilder\TcpProxyExternalServiceBuilder;
use Rancherize\Blueprint\ExternalService\ExternalServiceParser\ExternalServiceNameParser;
use Rancherize\Blueprint\ExternalService\ExternalServiceParser\ExternalServiceParser;
use Rancherize\Blueprint\ExternalService\ExternalServiceYamlWriter\ExternalServiceYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;
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

		$this->container['external-service-builder.builder-types.rancher-external'] = function($c) {

			$rancherBuilder = new RancherExternalServiceBuilder();

			$rancherBuilder->setHealthcheckParser($c['healthcheck-parser']);
			$rancherBuilder->setPublishParser($c['publish-urls-parser']);

			return $rancherBuilder;
		};

		$this->container['external-service-builder.builder-types.tcp-proxy'] = function($c) {
			$tcpBuilder = new TcpProxyExternalServiceBuilder();

			$tcpBuilder->setHealthcheckParser($c['healthcheck-parser']);
			$tcpBuilder->setPublishParser($c['publish-urls-parser']);

			return $tcpBuilder;
		};

		$this->container['external-service-builder-factory'] = function($c) {
			return new ContainerExternalServiceFactory($c);
		};

		$this->container['external-service-name-parser'] = function () {
			return new ExternalServiceNameParser();
		};

		/**
		 * @return ExternalServiceParser
		 */
		$this->container['external-service-parser'] = function($c) {
			$externalServiceParser = new ExternalServiceParser($c['external-service-name-parser'], $c['external-service-builder-factory']);

			return $externalServiceParser;
		};

		$this->container['external-service-yaml-writer'] = function() {
			return new ExternalServiceYamlWriter();
		};

		$this->container['external-service-service-writer-listener'] = function($c) {
			return new ExternalServiceEventListener( $c['external-service-yaml-writer'] );
		};

		$this->container['external-service-push-listener'] = function($c) {
			return new ExternalServicePushListener( $c['external-service-name-parser'] );
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

		$pushListener = $this->container['external-service-push-listener'];
		$event->addListener(PushCommandInServiceUpgradeEvent::NAME, [$pushListener, 'inServiceUpgrade']);
		$event->addListener(PushCommandStartEvent::NAME, [$pushListener, 'startService']);
	}
}