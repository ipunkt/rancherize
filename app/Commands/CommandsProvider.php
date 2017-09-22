<?php namespace Rancherize\Commands;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\Services\BuildService;
use Rancherize\Services\DockerService;
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

		$this->container['command.push'] = function($c) {
			$pushCommand =  new PushCommand( $c[RancherAccessService::class], $c[DockerService::class], $c[BuildService::class] );

			$pushCommand->setInServiceChecker( $c['in-service-checker'] );

			$pushCommand->setBlueprintService( $c['blueprint-service'] );

			return $pushCommand;
		};

		$this->container['command.build'] = function($c) {
			$buildCommand = new BuildCommand( $c[BuildService::class] );

			$buildCommand->setBlueprintService( $c['blueprint-service'] );

			return $buildCommand;
		};

		$this->container['command.start'] = function($c) {
			$startCommand =  new StartCommand( $c[BuildService::class] );

			$startCommand->setBlueprintService( $c['blueprint-service'] );

			return $startCommand;
		};

		$this->container['command.stop'] = function($c) {
			$stopCommand =  new StopCommand( $c[BuildService::class] );

			$stopCommand->setBlueprintService( $c['blueprint-service'] );

			return $stopCommand;
		};

		$this->container['command.init'] = function($c) {
			$initCommand =  new InitCommand( $c['rancher-access-service'] );

			$initCommand->setBlueprintService( $c['blueprint-service'] );

			return $initCommand;
		};

		$this->container['command.validate'] = function($c) {
			$validateCommand =  new ValidateCommand( $c[BuildService::class] );

			$validateCommand->setBlueprintService( $c['blueprint-service'] );

			return $validateCommand;
		};

		$this->container['command.restart'] = function($c) {
			$restartCommand =  new RestartCommand( $c[DockerService::class], $c[BuildService::class] );

			$restartCommand->setBlueprintService( $c['blueprint-service'] );

			return $restartCommand;
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var Application $app
		 */
		$app = $this->container['app'];

		$app->add( $this->container['command.push'] );
		$app->add( $this->container['command.build'] );
		$app->add( $this->container['command.start'] );
		$app->add( $this->container['command.stop'] );
		$app->add( $this->container['command.restart'] );
		$app->add( $this->container['command.validate'] );
		$app->add( $this->container['command.init'] );

		$app->add( $this->container['environment-version-command'] );
	}
}