<?php namespace Rancherize\Blueprint\Webserver;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Cron\CronInit\CronInit;
use Rancherize\Blueprint\Cron\CronParser\CronParser;
use Rancherize\Blueprint\ExternalService\ExternalServiceParser\ExternalServiceParser;
use Rancherize\Blueprint\Flags\HasFlagsTrait;
use Rancherize\Blueprint\Healthcheck\HealthcheckConfigurationToService\HealthcheckConfigurationToService;
use Rancherize\Blueprint\Healthcheck\HealthcheckInitService\HealthcheckInitService;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles\CustomFilesTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMakerTrait;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\Services\AppService;
use Rancherize\Blueprint\Infrastructure\Service\Services\LaravelQueueWorker;
use Rancherize\Blueprint\Infrastructure\Service\Services\RedisService;
use Rancherize\Blueprint\NginxSnippets\NginxSnippetParser\NginxSnippetParser;
use Rancherize\Blueprint\PublishUrls\PublishUrlsIniter\PublishUrlsInitializer;
use Rancherize\Blueprint\PublishUrls\PublishUrlsParser\PublishUrlsParser;
use Rancherize\Blueprint\Scheduler\SchedulerInitializer\SchedulerInitializer;
use Rancherize\Blueprint\Scheduler\SchedulerParser\SchedulerParser;
use Rancherize\Blueprint\Services\Database\DatabaseBuilder\DatabaseBuilder;
use Rancherize\Blueprint\TakesDockerAccount;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Blueprint\Validation\Traits\HasValidatorTrait;
use Rancherize\Configuration\ArrayAdder\ArrayAdder;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurableFallback;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Configuration\Services\ConfigurationInitializer;
use Rancherize\Docker\DockerAccount;
use Rancherize\RancherAccess\InServiceCheckerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WebserverBlueprint
 * @package Rancherize\Blueprint\Webserver
 *
 * This blueprint builds docker and rancher configuration for ipunktbs/nginx and ipunktbs/nginx-debug
 */
class WebserverBlueprint implements Blueprint, TakesDockerAccount {

	use HasFlagsTrait;

	use HasValidatorTrait;

	use PhpFpmMakerTrait;

	use CustomFilesTrait;

	use InServiceCheckerTrait;

	/**
	 * @var ArrayAdder
	 */
	protected $arrayAdder;

	/**
	 * @var DockerAccount
	 */
	protected $dockerAccount = null;

	/**
	 * @var Service
	 */
	private $appContainer;

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
			$initializer->init($fallbackConfigurable, 'add-database', false);
			$initializer->init($fallbackConfigurable, 'database.pma.enable', false);
			$initializer->init($fallbackConfigurable, 'database.pma.require-login', false);
			$initializer->init($fallbackConfigurable, 'database.pma.expose', false);

			do {
				$pmaPort = mt_rand($minPort, $maxPort);
			} while($pmaPort === $port);

			$initializer->init($fallbackConfigurable, 'database.pma-port', $pmaPort);

		} else {
			$initializer->init($fallbackConfigurable, 'external_links', [
				'Frontend/mysql-tunnel',
			]);
			$initializer->init($fallbackConfigurable, 'rancher.stack', 'Project');

			$healthcheckInit = new HealthcheckInitService($initializer);
			$healthcheckInit->init($fallbackConfigurable);

			$publishUrlsInit = new PublishUrlsInitializer($initializer);
			$publishUrlsInit->init($fallbackConfigurable);

			$schedulerInitializer = new SchedulerInitializer($initializer);
			$schedulerInitializer->init($fallbackConfigurable, $projectConfigurable);

			/**
			 * @var CronInit $cronInitializer
			 */
			$cronInitializer = container('cron-init');
			$cronInitializer->init( $fallbackConfigurable, $initializer );
		}

		$initializer->init($fallbackConfigurable, 'php', "7.0");
		$initializer->init($fallbackConfigurable, 'queues', []);
		$initializer->init($fallbackConfigurable, 'docker.repository', 'repo/name', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'docker.version-prefix', '', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'nginx-config', '', $projectConfigurable);
        $initializer->init($fallbackConfigurable, 'add-redis', false);
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
        $this->addVersionLabel($version, $config, $serverService);


		/**
		 * @var DatabaseBuilder $databaseBuilder
		 */
        $databaseBuilder = container('database-builder');
        $databaseBuilder->setAppService( $this->appContainer );
        $databaseBuilder->setServerService($serverService);
        $databaseBuilder->addDatabaseService($config, $serverService, $infrastructure);

        $this->getCustomFilesMaker()->make($config, $serverService, $infrastructure);

		$phpFpmMaker = $this->getPhpFpmMaker();
		$phpFpmMaker->make($config, $serverService, $infrastructure);

		$this->addQueueWorker($config, $serverService, $infrastructure);

		/**
		 * This adds -$VERSION to the server service and all its sidekicks unless in-service upgrades are activated
		 *
		 * Reason: doing a rolling-upgrade from one service to another requires both to exist in parallel. So all services
		 *  which belong to the ugprade need to have unique names, since rancher otherwise errors with `service name not unique`
		 */
        $this->addVersionSuffix($config, $serverService, $versionSuffix);

        // Add Healthcheck config
		/**
		 * @var HealthcheckConfigurationToService $healthcheckParser
		 */
		$healthcheckParser = container('healthcheck-parser');
		$healthcheckParser->parseToService( $serverService, $config );

		/**
		 * @var PublishUrlsParser $publishUrlsParser
		 */
		$publishUrlsParser = container('publish-urls-parser');
		$publishUrlsParser->parseToService( $serverService, $config );

		/**
		 * @var SchedulerParser $publishUrlsParser
		 */
		$publishUrlsParser = container('scheduler-parser');
		$publishUrlsParser->parse( $serverService, $config );

        $infrastructure->addService($serverService);

		/**
		 * @var ExternalServiceParser $externalServicesParser
		 */
        $externalServicesParser = container('external-service-parser');
        $externalServicesParser->parse($config, $infrastructure);

		/**
		 * @var NginxSnippetParser $nginxSnippetParser
		 */
        $nginxSnippetParser = container('nginx-snippets-parser');
        $nginxSnippetParser->parse( $serverService, $config );

		/**
		 * @var CronParser $cronParser
		 */
        $cronParser = container('cron-parser');
        $cronParser->parse($config, $infrastructure, function($name, $command) use ($phpFpmMaker, $serverService, $config) {
        	return $phpFpmMaker->makeCommand($name, $command, $serverService, $config);
        });

        return $infrastructure;
	}

	const WWW_DATA_USER_ID = 33;
	const WWW_DATA_GROUP_ID = 33;

	/**
	 * @param $config
	 * @return Dockerfile
	 */
	protected function makeDockerfile(Configuration $config):Dockerfile {
		$dockerfile = new Dockerfile();

		$dockerfile->setUser( self::WWW_DATA_USER_ID );
		$dockerfile->setGroup( self::WWW_DATA_GROUP_ID );

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
	 * @param Configuration $config
	 * @param Configuration $default
	 * @return Service
	 */
	protected function makeServerService(Configuration $config, Configuration $default) : Service {
		$serverService = new Service();
		$serverService->setName($config->get('service-name'));
		$serverService->setImage($config->get('docker.image', 'ipunktbs/nginx:1.10.2-7-1.3.0'));
		if( $config->get('debug-image', false) )
			$serverService->setImage($config->get('docker.image', 'ipunktbs/nginx-debug:debug-1.3.0'));

		if( $config->get('sync-user-into-container', false) ) {
			$serverService->setEnvironmentVariable('USER_ID',empty($_ENV['USER_ID']) ? getmyuid() : $_ENV['USER_ID'] );
			$serverService->setEnvironmentVariable('GROUP_ID', empty($_ENV['GROUP_ID']) ? getmygid() : $_ENV['GROUP_ID'] );
		}

		if ($config->has('expose-port'))
			$serverService->expose(80, $config->get('expose-port'));

		if ($config->get('mount-workdir', false)) {
			$mountSuffix = $config->get('work-sub-directory', '');
			$targetSuffix = $config->get('target-sub-directory', '');

			$nginxConfig = $config->get('nginx-config');
			if (!empty($nginxConfig)) {
				//$configName = basename($nginxConfig);
				$serverService->addVolume(getcwd() . DIRECTORY_SEPARATOR . $nginxConfig, '/etc/nginx/conf.template.d/999-laravel.conf.tpl');
			}

			$hostDirectory = getcwd() . $mountSuffix;
			$containerDirectory = '/var/www/app' . $targetSuffix;
			$serverService->addVolume($hostDirectory, $containerDirectory);
			$this->getPhpFpmMaker()->setAppMount($hostDirectory, $containerDirectory);
		}

		$persistentDriver = $config->get('docker.persistent-driver', 'pxd');
		$persistentOptions = $config->get('docker.persistent-options', [
			'repl' => '3',
			'shared' => 'true',
		]);
		foreach( $config->get('persistent-volumes', []) as $volumeName => $path ) {
			$volume = new \Rancherize\Blueprint\Infrastructure\Service\Volume();
			$volume->setDriver($persistentDriver);
			$volume->setOptions($persistentOptions);
			$volume->setExternalPath($volumeName);
			$volume->setInternalPath($path);
			$serverService->addVolume( $volume );
		}

		$this->arrayAdder->addAll([$default, $config], 'environment', function(string $name, $value) use ($serverService) {
			$serverService->setEnvironmentVariable($name, $value);
		});

		$this->arrayAdder->addAll([$default, $config], 'labels', function(string $name, $value) use ($serverService) {
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
	 * @param string $version
	 * @param Configuration $config
	 * @param Service $serverService
	 */
	protected function addVersionLabel($version, Configuration $config, Service $serverService) {
		$labelVersion = $version;
		if($version === null)
			$labelVersion = '';

		$serverService->addLabel('version', $labelVersion);
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
			$imageNameWithServer = $this->applyServer($imageName);

			$appService = new AppService($imageNameWithServer);
			$appService->setName($config->get('service-name') . 'App');

			$serverService->addSidekick($appService);
			$serverService->addVolumeFrom($appService);
			$infrastructure->addService($appService);
			$this->getPhpFpmMaker()->setAppService($appService);

			$this->appContainer = $appService;
		}
	}

	protected function applyServer(string $imageName) {
		if( $this->dockerAccount === null)
			return $imageName;

		$server = $this->dockerAccount->getServer();
		if( empty($server) )
			return $imageName;

		$serverHost = parse_url($server, PHP_URL_HOST);
		$imageNameWithServer = $serverHost.'/'.$imageName;

		return $imageNameWithServer;
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

	/**
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param Infrastructure $infrastructure
	 */
	protected function addQueueWorker(Configuration $config, Service $serverService, Infrastructure $infrastructure) {
	    $queues = $config->get('queues', []);
        foreach($queues as $key => $queue) {
            $name = $config->get("queues.$key.name", 'default');
            $connection = $config->get("queues.$key.connection", 'default');

            $laravelQueueWorker = new LaravelQueueWorker();
            $laravelQueueWorker->setName('QueueWorker' . ucwords($name));
            $laravelQueueWorker->addVolumeFrom($serverService);
            $laravelQueueWorker->addLinksFrom($serverService);
            $laravelQueueWorker->setEnvironmentVariablesFrom($serverService);

            $laravelQueueWorker->setEnvironmentVariable('QUEUE_NAME', $name);
            $laravelQueueWorker->setEnvironmentVariable('QUEUE_CONNECTION', $connection);

            $serverService->addSidekick($laravelQueueWorker);
            $infrastructure->addService($laravelQueueWorker);
        }
	}

	/**
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param $versionSuffix
	 */
	private function addVersionSuffix(Configuration $config, Service $serverService, $versionSuffix) {

		if( $this->getInServiceChecker()->isInService($config) )
			return;

		/**
		 * Add Version suffix to the main service and all its sidekicks
		 */
		$serverService->setName($serverService->getName() . $versionSuffix);
		foreach ($serverService->getSidekicks() as $sidekick)
			$sidekick->setName($sidekick->getName() . $versionSuffix);
	}

	/**
	 * @param DockerAccount $dockerAccount
	 * @return $this
	 */
	public function setDockerAccount( DockerAccount $dockerAccount ) {
		$this->dockerAccount = $dockerAccount;
		return $this;
	}

	/**
	 * @param ArrayAdder $arrayAdder
	 */
	public function setArrayAdder( ArrayAdder $arrayAdder ) {
		$this->arrayAdder = $arrayAdder;
	}

}
