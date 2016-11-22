<?php namespace Rancherize\Blueprint\Webserver;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Flags\HasFlagsTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Configuration\Configurable;
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
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function init(Configurable $configurable, InputInterface $input, OutputInterface $output) {

		$initializer = new ConfigurationInitializer($output);

		if( $this->getFlag('dev', false) ) {
			$initializer->init($configurable, 'IMAGE', 'ipunkt/nginx-debug');
			$port = mt_rand(9000, 20000);
			$initializer->init($configurable, 'EXPOSED_PORT', $port);
			$initializer->init($configurable, 'USE_APP_CONTAINER', false);
		} else {
			$initializer->init($configurable, 'IMAGE', 'ipunkt/nginx');
		}


	}


}