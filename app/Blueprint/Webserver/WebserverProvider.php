<?php namespace Rancherize\Blueprint\Webserver;

use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class WebserverProvider
 * @package Rancherize\Blueprint\Webserver
 */
class WebserverProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
	}

	/**
	 */
	public function boot() {
		/**
		 * @var BlueprintFactory $blueprintFactory
		 */
		$blueprintFactory = container('blueprint-factory');
		$blueprintFactory->add('webserver', 'Rancherize\Blueprint\Webserver\WebserverBlueprint');
	}
}