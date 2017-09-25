<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Commands\BlueprintAdd;
use Rancherize\Blueprint\Commands\BlueprintList;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\Services\BlueprintService;
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
			$environmentVersionCommand = new EnvironmentVersionCommand( $c[BlueprintService::class], $c[RancherAccessService::class] );

			$environmentVersionCommand->setInServiceChecker( $c['in-service-checker'] );

			return $environmentVersionCommand;
		};

		$this->container['command.push'] = function($c) {
			$pushCommand =  new PushCommand( $c[RancherAccessService::class], $c[DockerService::class], $c[BuildService::class], $c[BlueprintService::class]  );

			$pushCommand->setInServiceChecker( $c['in-service-checker'] );

			return $pushCommand;
		};

		$this->container['command.build'] = function($c) {
			$buildCommand = new BuildCommand( $c[BuildService::class], $c[BlueprintService::class]  );

			return $buildCommand;
		};

		$this->container['command.start'] = function($c) {
			$startCommand =  new StartCommand( $c[BuildService::class], $c[BlueprintService::class] );

			return $startCommand;
		};

		$this->container['command.stop'] = function($c) {
			$stopCommand =  new StopCommand( $c[BuildService::class], $c[BlueprintService::class]  );

			return $stopCommand;
		};

		$this->container['command.init'] = function($c) {
			$initCommand =  new InitCommand( $c['rancher-access-service'], $c[BlueprintService::class]  );

			return $initCommand;
		};

		$this->container['command.validate'] = function($c) {
			$validateCommand =  new ValidateCommand( $c[BuildService::class], $c[BlueprintService::class]  );

			return $validateCommand;
		};

		$this->container['command.restart'] = function($c) {
			$restartCommand =  new RestartCommand( $c[DockerService::class], $c[BuildService::class], $c[BlueprintService::class]  );

			return $restartCommand;
		};

		$this->container['command.blueprint.add'] = function($c) {
			$blueprintAddCommand = new BlueprintAdd( $c[BlueprintFactory::class] );

			return $blueprintAddCommand;
		};

		$this->container['command.blueprint.list'] = function($c) {
			$blueprintListCommand = new BlueprintList( $c[BlueprintFactory::class] );

			return $blueprintListCommand;
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
		$app->add( $this->container['command.blueprint.add'] );
		$app->add( $this->container['command.blueprint.list'] );

		$app->add( $this->container['environment-version-command'] );
	}
}