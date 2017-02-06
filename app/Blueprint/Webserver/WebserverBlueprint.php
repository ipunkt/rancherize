<?php namespace Rancherize\Blueprint\Webserver;
use Closure;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Flags\HasFlagsTrait;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles\CustomFilesTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMakerTrait;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\Services\AppService;
use Rancherize\Blueprint\Infrastructure\Service\Services\DatabaseService;
use Rancherize\Blueprint\Infrastructure\Service\Services\PmaService;
use Rancherize\Blueprint\Infrastructure\Service\Services\RedisService;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Blueprint\Validation\Traits\HasValidatorTrait;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurableFallback;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Configuration\Services\ConfigurationInitializer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WebserverBlueprint
 * @package Rancherize\Blueprint\Webserver
 *
 * This blueprint builds docker and rancher configuration for ipunktbs/nginx and ipunktbs/nginx-debug
 */
class WebserverBlueprint implements Blueprint {

	use HasFlagsTrait;

	use HasValidatorTrait;

	use PhpFpmMakerTrait;

	use CustomFilesTrait;

	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function init(Configurable $configurable, string $environment, InputInterface $input, OutputInterface $output) {

		$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.environments.$environment.");
		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.default.");
		$fallbackConfigurable = new ConfigurableFallback($environmentConfigurable, $projectConfigurable);

		$initializer = new ConfigurationInitializer($output);

		if( $this->getFlag('dev', false) ) {
			//$initializer->init($fallbackConfigurable, 'docker.image', 'ipunktbs/nginx-debug:debug-1.2.5');
			$initializer->init($fallbackConfigurable, 'debug-image', true);

			$minPort = $configurable->get('global.min-port', 9000);
			$maxPort = $configurable->get('global.max-port', 20000);
			$port = mt_rand($minPort, $maxPort);

			$initializer->init($fallbackConfigurable, 'sync-user-into-container', true);
			$initializer->init($fallbackConfigurable, 'expose-port', $port);
			$initializer->init($fallbackConfigurable, 'use-app-container', false);
			$initializer->init($fallbackConfigurable, 'mount-workdir', true);
			$initializer->init($fallbackConfigurable, 'add-redis', false);
			$initializer->init($fallbackConfigurable, 'add-database', false);
			$initializer->init($fallbackConfigurable, 'database.pma', false);
			$initializer->init($fallbackConfigurable, 'database.pma-expose', false);

			do {
				$pmaPort = mt_rand($minPort, $maxPort);
			} while($pmaPort === $port);

			$initializer->init($fallbackConfigurable, 'database.pma-port', $pmaPort);

		} else {

			$initializer->init($fallbackConfigurable, 'external_links', [
				'Frontend/mysql-tunnel',
			]);
			$initializer->init($fallbackConfigurable, 'rancher.stack', 'Project');
		}

		$initializer->init($fallbackConfigurable, 'php', "7.0");
		$initializer->init($fallbackConfigurable, 'docker.repository', 'repo/name', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'docker.version-prefix', '', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'nginx-config', '', $projectConfigurable);

		$initializer->init($fallbackConfigurable, 'service-name', 'Project', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'docker.base-image', 'busybox', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'environment', ["EXAMPLE" => 'value']);


	}


	/**
	 * @param Configuration $configurable
	 * @param string $environment
	 * @throws ValidationFailedException
	 */
	public function validate(Configuration $configurable, string $environment) {

		$projectConfigurable = new PrefixConfigurationDecorator($configurable, "project.default.");
		$environmentConfigurable = new PrefixConfigurationDecorator($configurable, "project.environments.$environment.");
		$config = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$this->getValidator()->validate($config, [
			'docker.base-image' => 'required',
			'service-name' => 'required',

		]);
	}

	/**
	 * @param Configurable $configuration
	 * @param string $environment
	 * @param string $version
	 * @return Infrastructure
	 */
	public function build(Configuration $configuration, string $environment, string $version = null) : Infrastructure {
		$infrastructure = new Infrastructure();

		$versionSuffix = '-'.$version;
		if($version === null)
			$versionSuffix = '';

		$projectConfigurable = new PrefixConfigurationDecorator($configuration, "project.default.");
		$environmentConfigurable = new PrefixConfigurationDecorator($configuration, "project.environments.$environment.");
		$config = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$dockerfile = $this->makeDockerfile($config);
		$infrastructure->setDockerfile($dockerfile);

		$serverService = $this->makeServerService($config, $projectConfigurable);
		$this->addRedis($config, $serverService, $infrastructure);

		$this->addAppContainer($version, $config, $serverService, $infrastructure);

		$this->addVersionEnvironment($version, $config, $serverService);

		$this->addDatabaseService($config, $serverService, $infrastructure);

		$this->getCustomFilesMaker()->make($config, $serverService, $infrastructure);

		$this->getPhpFpmMaker()->make($config, $serverService, $infrastructure);


		/**
		 * Add Version suffix to the main service and all its sidekicks
		 */
		$serverService->setName( $serverService->getName().$versionSuffix );
		foreach($serverService->getSidekicks() as $sidekick)
			$sidekick->setName( $sidekick->getName().$versionSuffix );

		$infrastructure->addService($serverService);

		return $infrastructure;
	}

	/**
	 * @param $config
	 * @return Dockerfile
	 */
	protected function makeDockerfile(Configuration $config):Dockerfile {
		$dockerfile = new Dockerfile();

		$dockerfile->setFrom($config->get('docker.base-image'));

		$dockerfile->addVolume('/var/www/app');

		$copySuffix = $config->get('work-sub-directory', '');
		$targetSuffix = $config->get('target-sub-directory', '');

		$dockerfile->copy('.'.$copySuffix, '/var/www/app'.$targetSuffix);

		$nginxConfig = $config->get('nginx-config');
		if (!empty($nginxConfig)) {
			$dockerfile->addVolume('/etc/nginx/conf.template.d');
			$dockerfile->copy($nginxConfig, '/etc/nginx/conf.template.d/');

		}

		$this->getCustomFilesMaker()->applyToDockerfile($config, $dockerfile);

		// TODO: Move to own function / service class
		$additionalFiles = $config->get('add-files');
		if( is_array($additionalFiles) ) {
			foreach($additionalFiles as $file => $path) {
				$dockerfile->copy($file, $path);
			}
		}

		$additionalVolumes = $config->get('add-volumes');
		if( is_array($additionalVolumes) ) {
			foreach($additionalFiles as $path) {
				$dockerfile->addVolume($path);
			}
		}

		$dockerfile->run('rm -Rf /var/www/app/.rancherize');
		$dockerfile->setCommand('/bin/true');
		return $dockerfile;
	}

	/**
	 * @param Configuration[] $configs
	 * @param string $label
	 * @param Closure $closure
	 *
	 * TODO: make service object
	 */
	private function addAll(array $configs, string $label, Closure $closure) {
		foreach($configs as $c) {
			if(!$c->has($label))
				continue;

			foreach ($c->get($label) as $name => $value)
				$closure($name, $value);
		}
	}

	/**
	 * @param Configuration $config
	 * @param Configuration $default
	 * @return Service
	 */
	protected function makeServerService(Configuration $config, Configuration $default) : Service {
		$serverService = new Service();
		$serverService->setName($config->get('service-name'));
		$serverService->setImage($config->get('docker.image', 'ipunktbs/nginx:1.9.7-7-1.2.8'));
		if( $config->get('debug-image', false) )
			$serverService->setImage($config->get('docker.image', 'ipunktbs/nginx-debug:debug-1.2.8'));

		if( $config->get('sync-user-into-container', false) ) {
			$serverService->setEnvironmentVariable('USER_ID', getmyuid());
			$serverService->setEnvironmentVariable('GROUP_ID', getmygid());
		}

		if ($config->has('expose-port'))
			$serverService->expose(80, $config->get('expose-port'));

		if ($config->get('mount-workdir', false)) {
			$mountSuffix = $config->get('work-sub-directory', '');
			$targetSuffix = $config->get('target-sub-directory', '');

			$nginxConfig = $config->get('nginx-config');
			if (!empty($nginxConfig)) {
				$configName = basename($nginxConfig);
				$serverService->addVolume(getcwd() . DIRECTORY_SEPARATOR . $nginxConfig, '/etc/nginx/conf.template.d/999-laravel.conf.tpl');
			}

			$hostDirectory = getcwd() . $mountSuffix;
			$containerDirectory = '/var/www/app' . $targetSuffix;
			$serverService->addVolume($hostDirectory, $containerDirectory);
			$this->getPhpFpmMaker()->setAppMount($hostDirectory, $containerDirectory);
		}

		$this->addAll([$default, $config], 'environment', function(string $name, $value) use ($serverService) {
			$serverService->setEnvironmentVariable($name, $value);
		});

		$this->addAll([$default, $config], 'labels', function(string $name, $value) use ($serverService) {
			$serverService->addLabel($name, $value);
		});

		if ($config->has('external_links')) {
			foreach ($config->get('external_links') as $name => $value)
				$serverService->addExternalLink($value, $name);
		}

		return $serverService;
	}

	/**
	 * @param string $version
	 * @param Configuration $config
	 * @param Service $serverService
	 */
	protected function addVersionEnvironment($version, Configuration $config, Service $serverService) {
		/**
		 * Version
		 */
		$versionEnvironmentVariable = $config->get('add-version');
		if ($versionEnvironmentVariable === null)
			return;

		$environmentVersion = $version;
		if ($version === null)
			$environmentVersion = 'not set';
		$serverService->setEnvironmentVariable($versionEnvironmentVariable, $environmentVersion);
	}

	/**
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param Infrastructure $infrastructure
	 */
	protected function addDatabaseService(Configuration $config, Service $serverService, Infrastructure $infrastructure) {
		if ($config->get('add-database', false)) {
			$databaseService = new DatabaseService();


			if ($config->has('database.name'))
				$databaseService->setDatabaseName($config->get('database.name'));
			if ($config->has('database.user'))
				$databaseService->setDatabaseUser($config->get('database.user'));
			if ($config->has('database.password'))
				$databaseService->setDatabasePassword($config->get('database.password'));

			$serverService->addLink($databaseService, 'database-master');
			$serverService->setEnvironmentVariable('DATABASE_NAME', $databaseService->getDatabaseName());
			$serverService->setEnvironmentVariable('DATABASE_USER', $databaseService->getDatabaseUser());
			$serverService->setEnvironmentVariable('DATABASE_PASSWORD', $databaseService->getDatabasePassword());

			/**
			 * Laravel 5.3 compatibility env vars https://ipunkt-intern.demobereich.de/trac/ticket/217#comment:1
			 */
			$serverService->setEnvironmentVariable('DB_HOST', 'database-master');
			$serverService->setEnvironmentVariable('DB_PORT', 3306);
			$serverService->setEnvironmentVariable('DB_DATABASE', $databaseService->getDatabaseName());
			$serverService->setEnvironmentVariable('DB_USERNAME', $databaseService->getDatabaseUser());
			$serverService->setEnvironmentVariable('DB_PASSWORD', $databaseService->getDatabasePassword());

			$infrastructure->addService($databaseService);


			/**
			 * PMA
			 */
			if ($config->get('database.pma', true)) {
				$pmaService = new PmaService();
				$pmaService->addLink($databaseService, 'db');
				if ($config->get('database.pma-expose', true))
					$pmaService->expose(80, $config->get('database.pma-port', 8082));

				$infrastructure->addService($pmaService);
			}
		}
	}

	/**
	 * @param string $version
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param Infrastructure $infrastructure
	 */
	protected function addAppContainer($version, Configuration $config, Service $serverService, Infrastructure $infrastructure) {
		if ($config->get('use-app-container', true)) {

			$imageName = $config->get('docker.repository') . ':' . $config->get('docker.version-prefix') . $version;
			$appService = new AppService($imageName);
			$appService->setName($config->get('service-name') . 'App');

			$serverService->addSidekick($appService);
			$serverService->addVolumeFrom($appService);
			$infrastructure->addService($appService);
			$this->getPhpFpmMaker()->setAppService($appService);
		}
	}

	/**
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param Infrastructure $infrastructure
	 */
	protected function addRedis(Configuration $config, Service $serverService, Infrastructure $infrastructure) {
		if ($config->get('add-redis', false)) {
			$redisService = new RedisService();
			$serverService->addLink($redisService, 'redis');
			$serverService->setEnvironmentVariable('REDIS_HOST', 'redis');
			$serverService->setEnvironmentVariable('REDIS_PORT', '6379');
			$infrastructure->addService($redisService);
		}
	}

}