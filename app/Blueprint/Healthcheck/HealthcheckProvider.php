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
        $this->container[HealthcheckYamlWriter::class] = function() {
            return new HealthcheckYamlWriter();
        };
        $this->container['healthcheck-yaml-writer'] = function($c) {
            return $c[HealthcheckYamlWriter::class];
        };

        $this->container[HealthcheckServiceWriterListener::class] = function ($c) {
            return new HealthcheckServiceWriterListener( $c[HealthcheckYamlWriter::class] );
        };
		$this->container['healthcheck-service-writer-listener'] = function ($c) {
			return $c[HealthcheckServiceWriterListener::class];
		};

        $this->container[HealthcheckDefaultInformationSetter::class] = function() {
            return new HealthcheckDefaultInformationSetter;
        };
		$this->container['healthcheck-default-setter'] = function($c) {
			return $c[HealthcheckDefaultInformationSetter::class];
		};

        $this->container[HealthcheckConfigurationToService::class] = function ($c) {
            return new HealthcheckConfigurationToService( $c['healthcheck-default-setter'] );
        };
		$this->container['healthcheck-parser'] = function ($c) {
			return $c[HealthcheckConfigurationToService::class];
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