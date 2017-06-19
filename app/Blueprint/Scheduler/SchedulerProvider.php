<?php namespace Rancherize\Blueprint\Scheduler;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Scheduler\EventListener\SchedulerServiceWriterListener;
use Rancherize\Blueprint\Scheduler\SchedulerParser\SchedulerParser;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher\RancherTagService;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher\V2\V2RancherSchedulerYamlWriter;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\SchedulerYamlWriter;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SchedulerProvider
 * @package Rancherize\Blueprint\Scheduler
 */
class SchedulerProvider implements Provider {

	use ProviderTrait;


	/**
	 */
	public function register() {
		$this->container['scheduler-parser'] = function($c) {
			return new SchedulerParser();
		};
		$this->container['scheduler-yaml-writer'] = function($c) {
			return new SchedulerYamlWriter();
		};
		$this->container['scheduler-service-writer-listener'] = function($c) {
			return new SchedulerServiceWriterListener($c['scheduler-yaml-writer']);
		};

		$this->container['rancher-tag-service'] = function($c) {
			return new RancherTagService();
		};
		$this->container['v2-rancher-scheduler-yaml-writer'] = function($c) {
			return new V2RancherSchedulerYamlWriter( $c['rancher-tag-service'] );
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['scheduler-service-writer-listener'];
		$event->addListener(ServiceWriterServicePreparedEvent::NAME, [$listener, 'servicePrepared']);
	}
}