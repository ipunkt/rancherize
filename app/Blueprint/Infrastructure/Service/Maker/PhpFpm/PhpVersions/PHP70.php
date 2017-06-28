<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersion;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP70
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP70 implements PhpVersion {

	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {
		/**
		 * PHP7.0 is started by default, no fpm service needs to be added
		 */
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return '7.0';
	}

	/**
	 * @param string $hostDirectory
	 * @param string $containerDirectory
	 * @return $this
	 */
	public function setAppMount(string $hostDirectory, string $containerDirectory) {
		/**
		 * Nothing to do while fpm 7.0 is still used from internal
		 */
		return $this;
	}

	/**
	 * @param Service $appService
	 * @return $this
	 */
	public function setAppService(Service $appService) {
		/**
		 * Nothing to do while fpm 7.0 is still used from internal
		 */
		return $this;
	}

	/**
	 * @param $commandName
	 * @param $command
	 * @param Service $mainService
	 * @return Service|void
	 */
	public function makeCommand( $commandName, $command, Service $mainService) {
		die('Error: PHP Commands not Yet implemented for PHP7');
	}
}