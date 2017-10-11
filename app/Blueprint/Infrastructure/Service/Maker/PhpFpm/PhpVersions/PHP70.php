<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\MemoryLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersion;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PostLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\UploadFileLimit;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP70
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP70 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit {

	const PHP_IMAGE = 'php:7.0-fpm';

	const CONFIG_IMAGE = 'ipunktbs/php-config:7.0-fpm';

	/**
	 * @var string
	 */
	private $memoryLimit = self::DEFAULT_MEMORY_LIMIT;

	/**
	 * @var string
	 */
	private $uploadFileLimit = self::DEFAULT_UPLOAD_FILE_LIMIT;

	/**
	 * @var string
	 */
	private $postLimit = self::DEFAULT_POST_LIMIT;

	public function make( Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		$phpFpmService = new Service();
		$phpFpmService->setName($mainService->getName().'-PHP-FPM');
		$phpFpmService->setImage( self::PHP_IMAGE );
		$phpFpmService->setRestart( Service::RESTART_UNLESS_STOPPED );

		$phpFpmConfigurationService = new Service();
		$phpFpmService->setName($mainService->getName().'-PHP-FPM-Config');
		$phpFpmService->setImage( self::CONFIG_IMAGE );
		$phpFpmService->setRestart( Service::RESTART_UNLESS_STOPPED );

		$phpFpmService->addVolumeFrom( $phpFpmConfigurationService );

		$memoryLimit = $this->memoryLimit;
		if( $memoryLimit !== self::DEFAULT_MEMORY_LIMIT)
			$phpFpmService->setEnvironmentVariable('PHP_MEMORY_LIMIT', $memoryLimit);

		$postLimit = $this->postLimit;
		if( $postLimit !== self::DEFAULT_POST_LIMIT)
			$phpFpmService->setEnvironmentVariable('PHP_POST_MAX_SIZE', $postLimit);

		$uploadFileLimit = $this->uploadFileLimit;
		if( $uploadFileLimit !== self::DEFAULT_UPLOAD_FILE_LIMIT)
			$phpFpmService->setEnvironmentVariable('PHP_UPLOAD_MAX_FILESIZE', $uploadFileLimit);

		$infrastructure->addService( $phpFpmService );
		$infrastructure->addService( $phpFpmConfigurationService );
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

	/**
	 * @return $this
	 */
	public function setMemoryLimit( $limit ) {
		$this->memoryLimit = $limit;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setPostLimit( $limit ) {
		$this->postLimit = $limit;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setUploadFileLimit( $limit ) {
		$this->uploadFileLimit = $limit;
		return $this;
	}
}