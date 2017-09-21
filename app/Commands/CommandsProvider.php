<?php namespace Rancherize\Commands;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Symfony\Component\Console\Application;

/**
 * Class CommandsProvider
 * @package Rancherize\Commands
 */
class CommandsProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['environment-version-command'] = function($c) {
			$environmentVersionCommand = new EnvironmentVersionCommand();

			$environmentVersionCommand->setInServiceChecker( $c['in-service-checker'] );

			return $environmentVersionCommand;
		};

		$this->container['push-command'] = function($c) {
			$pushCommand =  new PushCommand();

			$pushCommand->setInServiceChecker( $c['in-service-checker'] );

			return $pushCommand;
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var Application $app
		 */
		$app = $this->container['app'];

		$app->add( $this->container['push-command'] );
		$app->add( $this->container['environment-version-command'] );
	}
}