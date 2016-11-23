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
use Rancherize\Configuration\Services\ConfigurableFallback;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Configuration\Services\ConfigurationInitializer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WebserverBlueprint
 * @package Rancherize\Blueprint\Webserver
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

		$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.$environment.");
		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.");
		$fallbackConfigurable = new ConfigurableFallback($environmentConfigurable, $projectConfigurable);

		$initializer = new ConfigurationInitializer($output);

		if( $this->getFlag('dev', false) ) {
			$initializer->init($fallbackConfigurable, 'IMAGE', 'ipunktbs/nginx-debug');

			$minPort = $configurable->get('global.min-port', 9000);
			$maxPort = $configurable->get('global.max-port', 20000);
			$port = mt_rand($minPort, $maxPort);

			$initializer->init($fallbackConfigurable, 'EXPOSED_PORT', $port);
			$initializer->init($fallbackConfigurable, 'USE_APP_CONTAINER', false);
			$initializer->init($fallbackConfigurable, 'MOUNT_REPOSITORY', true);
			$initializer->init($fallbackConfigurable, 'ADD_REDIS', false);

		} else {

			$initializer->init($fallbackConfigurable, 'external_links', [
				'Frontend/mysql-tunnel',
			]);
		}

		$initializer->init($fallbackConfigurable, 'repository', 'repo/name', $projectConfigurable);

		$initializer->init($fallbackConfigurable, 'stack', 'Project', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'NAME', 'Project', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'BASE_IMAGE', 'busybox', $projectConfigurable);
		$initializer->init($fallbackConfigurable, 'environment', ["EXAMPLE" => 'value']);


	}


	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @throws ValidationFailedException
	 */
	public function validate(Configurable $configurable, string $environment) {

		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.");
		$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.$environment.");
		$config = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$required = [
			'BASE_IMAGE',
			'NAME',
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
	 * @param Configurable $configurable
	 * @param string $environment
	 * @param string $imageName
	 * @param string $version
	 * @return Infrastructure
	 */
	public function build(Configurable $configurable, string $environment, string $version = null) : Infrastructure {
		$infrastructure = new Infrastructure();

		$versionSuffix = '-'.$version;
		if($version === null)
			$versionSuffix = '';

		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.");
		$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.$environment.");
		$config = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$dockerfile = $this->makeDockerfile($config);
		$infrastructure->setDockerfile($dockerfile);

		$serverService = $this->makeServerService($config);
		if( $config->get('ADD_REDIS', false) ) {
			$redisService = new RedisService();
			$serverService->addLink($redisService, 'redis');
			$serverService->setEnvironmentVariable('REDIS_HOST', 'redis');
			$serverService->setEnvironmentVariable('REDIS_PORT', '6379');
			$infrastructure->addService($redisService);
		}

		if( $config->get('USE_APP_CONTAINER', true) ) {

			$imageName = $config->get('repository').'-'.$version;
			$appService = new AppService( $imageName );
			$appService->setName($config->get('NAME').'-App');

			$serverService->addSidekick($appService);
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

		$dockerfile->setFrom($config->get('BASE_IMAGE'));

		$dockerfile->addVolume('/var/www/app');
		$dockerfile->copy('.', '/var/www/app');

		$nginxConfig = $config->get('NGINX_CONFIG');
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
		$serverService->setName($config->get('NAME'));
		$serverService->setImage($config->get('IMAGE', 'ipunktbs/nginx:1.9.7-7-1.2.0'));

		if ($config->has('EXPOSED_PORT'))
			$serverService->expose(80, $config->get('EXPOSED_PORT'));

		if ($config->get('MOUNT_REPOSITORY', false))
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