<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Commands\BlueprintList;
use Rancherize\Blueprint\Factory\BlueprintFactory;
use Rancherize\Configuration\Services\EnvironmentConfigurationService;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Rancherize\Docker\DockerAccessService;
use Rancherize\Plugin\Commands\PluginInstallCommand;
use Rancherize\Plugin\Commands\PluginRegisterCommand;
use Rancherize\Plugin\Installer\PluginInstaller;
use Rancherize\Plugin\Loader\PluginLoader;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Push\CreateModeFactory\CreateModeFactory;
use Rancherize\Push\ModeFactory\PushModeFactory;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;
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
			$environmentVersionCommand = new EnvironmentVersionCommand( $c[BlueprintService::class],
					$c[RancherAccessService::class], $c[EnvironmentConfigurationService::class],
					$c[RancherService::class], $c[InServiceChecker::class] );

			return $environmentVersionCommand;
		};

		$this->container['command.push'] = function($c) {
			$pushCommand =  new PushCommand( $c[RancherAccessService::class], $c[DockerService::class],
				$c[BuildService::class], $c[BlueprintService::class], $c[EnvironmentConfigurationService::class],
				$c[DockerAccessService::class], $c[RancherService::class],
				$c[ReplaceUpgradeChecker::class], $c[PushModeFactory::class], $c[CreateModeFactory::class]  );

			return $pushCommand;
		};

        $this->container['command.build-image'] = function ($c) {
            $buildImageCommand = new BuildImageCommand($c[RancherAccessService::class], $c[DockerService::class],
                $c[BuildService::class], $c[BlueprintService::class], $c[EnvironmentConfigurationService::class],
                $c[DockerAccessService::class], $c[RancherService::class],
                $c[ReplaceUpgradeChecker::class], $c[PushModeFactory::class], $c[CreateModeFactory::class]);

            return $buildImageCommand;
        };

		$this->container['command.build'] = function($c) {
			$buildCommand = new BuildCommand( $c[BuildService::class], $c[BlueprintService::class]  );

			return $buildCommand;
		};

		$this->container['command.start'] = function($c) {
			$startCommand =  new StartCommand( $c[DockerService::class], $c[BuildService::class], $c[BlueprintService::class], $c[EnvironmentConfigurationService::class] );

			return $startCommand;
		};

		$this->container['command.stop'] = function($c) {
			$stopCommand =  new StopCommand( $c[DockerService::class], $c[BuildService::class], $c[BlueprintService::class], $c[EnvironmentConfigurationService::class]  );

			return $stopCommand;
		};

		$this->container['command.init'] = function($c) {
			$initCommand =  new InitCommand( $c[RancherAccessService::class], $c[BlueprintService::class], $c[DockerAccessService::class]  );

			return $initCommand;
		};

		$this->container['command.validate'] = function($c) {
			$validateCommand =  new ValidateCommand( $c[BuildService::class], $c[BlueprintService::class]  );

			return $validateCommand;
		};

		$this->container['command.restart'] = function($c) {
			$restartCommand =  new RestartCommand( $c[DockerService::class], $c[BuildService::class], $c[BlueprintService::class], $c[EnvironmentConfigurationService::class]  );

			return $restartCommand;
		};

		$this->container['command.blueprint.list'] = function($c) {
			$blueprintListCommand = new BlueprintList( $c[BlueprintFactory::class], $c[ProjectConfiguration::class], $c['configuration'] );

			return $blueprintListCommand;
		};

		$this->container['command.environment.set'] = function($c) {
			return new EnvironmentSetCommand( $c[EnvironmentConfigurationService::class] );
		};

		$this->container['command.rancher.access'] = function() {
			return new RancherAccessCommand();
		};

		$this->container['command.plugin.install'] = function($c) {
			return new PluginInstallCommand(  $c[PluginLoader::class], $c[PluginInstaller::class] );
		};

		$this->container['command.plugin.register'] = function($c) {
			return new PluginRegisterCommand( $c[PluginLoader::class], $c[PluginInstaller::class] );
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
        $app->add($this->container['command.build-image']);
		$app->add( $this->container['command.build'] );
		$app->add( $this->container['command.start'] );
		$app->add( $this->container['command.stop'] );
		$app->add( $this->container['command.restart'] );
		$app->add( $this->container['command.validate'] );
		$app->add( $this->container['command.init'] );
		$app->add( $this->container['command.blueprint.list'] );
		$app->add( $this->container['command.environment.set'] );
		$app->add( $this->container['command.rancher.access'] );
		$app->add( $this->container['command.plugin.install'] );
		$app->add( $this->container['command.plugin.register'] );

		$app->add( $this->container['environment-version-command'] );
	}
}