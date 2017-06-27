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
	 * @var string|Service
	 */
	protected $appTarget;

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
		$phpFpmService->setImage('ipunktbs/php-fpm:53-1.0.7');
		$phpFpmService->setRestart(Service::RESTART_UNLESS_STOPPED);

		$this->addAppSource($phpFpmService);

		/**
		 * Copy environment variables because environment variables are expected to be available in php
		 */
		foreach( $mainService->getEnvironmentVariables() as $name => $value )
			$phpFpmService->setEnvironmentVariable($name, $value);

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

	/**
	 * @param string $hostDirectory
	 * @param string $containerDirectory
	 * @return $this
	 */
	public function setAppMount(string $hostDirectory, string $containerDirectory) {
		$this->appTarget = [$hostDirectory, $containerDirectory];
		return $this;
	}

	/**
	 * @param Service $appService
	 * @return $this
	 */
	public function setAppService(Service $appService) {
		$this->appTarget = $appService;
		return $this;
	}

	/**
	 * @param $phpFpmService
	 */
	protected function addAppSource(Service $phpFpmService) {
		$appTarget = $this->appTarget;

		if ($appTarget instanceof Service) {
			$phpFpmService->addVolumeFrom($appTarget);
			return;
		}

		list($hostDirectory, $containerDirectory) = $appTarget;
		$phpFpmService->addVolume($hostDirectory, $containerDirectory);
	}
}