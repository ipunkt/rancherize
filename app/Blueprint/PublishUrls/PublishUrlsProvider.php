<?php namespace Rancherize\Blueprint\PublishUrls;

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
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['publish-urls-service-writer-listener'];
		$event->addListener(ServiceWriterRancherServicePreparedEvent::NAME, [$listener, 'rancherServicePrepared']);
	}
}