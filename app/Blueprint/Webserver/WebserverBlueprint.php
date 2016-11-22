<?php namespace Rancherize\Blueprint\Webserver;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Flags\HasFlagsTrait;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurableDecorator;
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

		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.$environment.");

		$initializer = new ConfigurationInitializer($output);

		if( $this->getFlag('dev', false) ) {
			$initializer->init($projectConfigurable, 'IMAGE', 'ipunktbs/nginx-debug');

			$minPort = $configurable->get('global.min-port', 9000);
			$maxPort = $configurable->get('global.max-port', 20000);
			$port = mt_rand($minPort, $maxPort);

			$initializer->init($projectConfigurable, 'EXPOSED_PORT', $port);
			$initializer->init($projectConfigurable, 'USE_APP_CONTAINER', false);
		} else {
			$initializer->init($projectConfigurable, 'IMAGE', 'ipunkt/nginx');
		}

		$initializer->init($projectConfigurable, 'NAME', 'Project');
		$initializer->init($projectConfigurable, 'BASE_IMAGE', 'busybox');


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
			'NAME'
		];

		$errors = [

		];

		foreach($required as $key) {

			if( !$config->has($key))
				$errors[] = "Missing required variable '$key'";

		}

		if( !empty($errors) )
			throw new ValidationFailedException($errors);

	}

	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @return mixed
	 */
	public function build(Configurable $configurable, string $environment) {
		$infrastructure = new Infrastructure();

		$projectConfigurable = new PrefixConfigurableDecorator($configurable, "project.");
		$environmentConfigurable = new PrefixConfigurableDecorator($configurable, "project.$environment.");
		$config = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$dockerfile = $this->makeDockerfile($config);
		$infrastructure->setDockerfile($dockerfile);

		$serverService = new Service();
		$serverService->setName( $config->get('NAME') );
		$serverService->setImage( $config->get('IMAGE', 'ipunktbs/nginx') );

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
}