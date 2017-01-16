<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersion;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP53
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP53 implements PhpVersion {

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {
		/**
		 * Disable internal fpm 7.0
		 */
		$mainService->setEnvironmentVariable('NO_FPM', 'true');

		$phpFpmService = new Service();
		$phpFpmService->setName('PHP-FPM');
		$phpFpmService->setImage('ipunktbs/php-fpm');
		$phpFpmService->setRestart(Service::RESTART_UNLESS_STOPPED);

		$mainService->addSidekick($phpFpmService);
		$mainService->addVolumeFrom($phpFpmService);
		$infrastructure->addService($phpFpmService);
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return '5.3';
	}
}