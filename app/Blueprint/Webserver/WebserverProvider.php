<?php namespace Rancherize\Blueprint\Webserver;

use Pimple\Container;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\UpgradeMode\RollingUpgradeChecker;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
		$blueprintFactory = $this->container[BlueprintFactory::class];
		$blueprintFactory->add('webserver', function(Container $c) {
			$webserverBlueprint = new WebserverBlueprint(
				$c[RollingUpgradeChecker::class],
				$c[EventDispatcher::class]
			);

			$webserverBlueprint->setArrayAdder( $c['config-array-adder'] );

			$webserverBlueprint->setProjectNameService($c['project-name-service']);

			$webserverBlueprint->setMailtrapService($c['mailtrap-service']);

			$webserverBlueprint->setSlashPrefixer( $c['slash-prefixer'] );

			return $webserverBlueprint;
		});

	}
}