<?php namespace Rancherize\Blueprint\Webserver;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Flags\HasFlagsTrait;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\Services\AppService;
use Rancherize\Blueprint\Infrastructure\Service\Services\RedisService;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
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
			$initializer->init($fallbackConfigurable, 'docker.image', 'ipunktbs/nginx-debug');

			$minPort = $configurable->get('global.min-port', 9000);
			$maxPort = $configurable->get('global.max-port', 20000);
			$port = mt_rand($minPort, $maxPort);

			$initializer->init($fallbackConfigurable, 'expose-port', $port);
			$initializer->init($fallbackConfigurable, 'use-app-container', false);
			$initializer->init($fallbackConfigurable, 'mount-workdir', true);
			$initializer->init($fallbackConfigurable, 'add-redis', false);

		} else {

			$initializer->init($fallbackConfigurable, 'external_links', [
				'Frontend/mysql-tunnel',
			]);
			$initializer->init($fallbackConfigurable, 'rancher.stack', 'Project');
		}

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

		$required = [
			'docker.base-image',
			'service-name',
		];

		$errors = [

		];

		foreach($required as $key) {

			if( !$config->has($key))
				$errors[$key] = "Missing.";

		}

		if( !empty($errors) )
			throw new ValidationFailedException($errors);

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

		$serverService = $this->makeServerService($config);
		if( $config->get('add-redis', false) ) {
			$redisService = new RedisService();
			$serverService->addLink($redisService, 'redis');
			$serverService->setEnvironmentVariable('REDIS_HOST', 'redis');
			$serverService->setEnvironmentVariable('REDIS_PORT', '6379');
			$infrastructure->addService($redisService);
		}

		if( $config->get('use-app-container', true) ) {

			$imageName = $config->get('docker.repository').':'.$config->get('docker.version-prefix').$version;
			$appService = new AppService( $imageName );
			$appService->setName($config->get('service-name').'-App');

			$serverService->addSidekick($appService);
			$serverService->addVolumeFrom($appService);
			$infrastructure->addService($appService);
		}

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
		$dockerfile->copy('.', '/var/www/app');

		$nginxConfig = $config->get('nginx-config');
		if (!empty($nginxConfig)) {
			$dockerfile->addVolume('/etc/nginx/conf.template.d');
			$dockerfile->copy($nginxConfig, '/etc/nginx/conf.template.d/');

		}

		$dockerfile->run('rm -Rf /var/www/app/.rancherize');
		$dockerfile->setCommand('/bin/true');
		return $dockerfile;
	}

	/**
	 * @param $config
	 * @return Service
	 */
	protected function makeServerService(Configuration $config) : Service {
		$serverService = new Service();
		$serverService->setName($config->get('service-name'));
		$serverService->setImage($config->get('docker.image', 'ipunktbs/nginx:1.9.7-7-1.2.0'));

		if ($config->has('expose-port'))
			$serverService->expose(80, $config->get('expose-port'));

		if ($config->get('mount-workdir', false))
			$serverService->addVolume(getcwd(), '/var/www/app');

		if ($config->has('environment')) {
			foreach ($config->get('environment') as $name => $value)
				$serverService->setEnvironmentVariable($name, $value);
		}

		if ($config->has('external_links')) {
			foreach ($config->get('external_links') as $name => $value)
				$serverService->addExternalLink($value, $name);
		}

		return $serverService;
	}
}