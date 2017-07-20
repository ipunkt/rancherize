<?php namespace Rancherize\Blueprint\PublishUrls;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\PublishUrls\EventListener\PublishUrlsServiceWriterListener;
use Rancherize\Blueprint\PublishUrls\PublishUrlsParser\PublishUrlsParser;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PublishUrlsYamlWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\V2TraefikPublishUrlsYamlWriterVersion;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class PublishUrlsProvider
 * @package Rancherize\Blueprint\PublishUrls
 */
class PublishUrlsProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['publish-urls-yaml-writer'] = function($c) {
			return new PublishUrlsYamlWriter($c);
		};

		$this->container['publish-urls-service-writer-listener'] = function($c) {
			return new PublishUrlsServiceWriterListener( $c['publish-urls-yaml-writer'] );
		};

		$this->container['publish-urls-yaml-writer.traefik.2'] = function($c) {
			return new V2TraefikPublishUrlsYamlWriterVersion();
		};

		$this->container['publish-urls-parser'] = function($c) {
			return new PublishUrlsParser();
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['publish-urls-service-writer-listener'];
		$event->addListener(ServiceWriterServicePreparedEvent::NAME, [$listener, 'servicePrepared']);
	}
}