<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\DefaultTimezone;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\MemoryLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersion;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PostLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\UploadFileLimit;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP53
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP53 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit, DefaultTimezone {

	const PHP_IMAGE = 'ipunktbs/php-fpm:1.2.0';

	/**
	 * @var string|Service
	 */
	protected $appTarget;

	/**
	 * @var string
	 */
	protected $memoryLimit = self::DEFAULT_MEMORY_LIMIT;

	/**
	 * @var string
	 */
	protected $postLimit = self::DEFAULT_POST_LIMIT;

	/**
	 * @var string
	 */
	protected $uploadFileLimit = self::DEFAULT_UPLOAD_FILE_LIMIT;

	/**
	 * @var string
	 */
	protected $defaultTimezone = self::DEFAULT_TIMEZONE;

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
		$phpFpmService->setImage( self::PHP_IMAGE );
		$phpFpmService->setRestart(Service::RESTART_UNLESS_STOPPED);

		$memoryLimit = $this->memoryLimit;
		if( $memoryLimit !== self::DEFAULT_MEMORY_LIMIT )
			$phpFpmService->setEnvironmentVariable('PHP_MEMORY_LIMIT', $memoryLimit);
		$postLimit = $this->postLimit;
		if( $postLimit !== self::DEFAULT_POST_LIMIT )
			$phpFpmService->setEnvironmentVariable('PHP_POST_MAX_SIZE', $postLimit);
		$uploadFileLimit = $this->uploadFileLimit;
		if( $uploadFileLimit !== self::DEFAULT_UPLOAD_FILE_LIMIT )
			$phpFpmService->setEnvironmentVariable('PHP_UPLOAD_MAX_FILESIZE', $uploadFileLimit);
		$defaultTimezone = $this->defaultTimezone;
		if( $defaultTimezone !== self::DEFAULT_TIMEZONE)
			$phpFpmService->setEnvironmentVariable('DEFAULT_TIMEZONE', $defaultTimezone);

		$this->addAppSource($phpFpmService);

		/**
		 * Copy environment variables because environment variables are expected to be available in php
		 */
		foreach( $mainService->getEnvironmentVariables() as $name => $value )
			$phpFpmService->setEnvironmentVariable($name, $value);

		/**
		 * Copy links from the main service so databases etc are available
		 */
		$phpFpmService->addLinksFrom($mainService);

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

	/**
	 * @param $commandName
	 * @param Service $command
	 * @param Service $mainService
	 * @return Service
	 */
	public function makeCommand( $commandName, $command, Service $mainService ) {

		$phpCommandService = new Service();
		$phpCommandService->setCommand($command);
		$phpCommandService->setName('PHP-'.$commandName);
		$phpCommandService->setImage( self::PHP_IMAGE );
		$phpCommandService->setRestart(Service::RESTART_START_ONCE);
		$this->addAppSource($phpCommandService);

		/**
		 * Copy environment variables because environment variables are expected to be available in php
		 */
		foreach( $mainService->getEnvironmentVariables() as $name => $value )
			$phpCommandService->setEnvironmentVariable($name, $value);

		$mainService->addSidekick($phpCommandService);
		return $phpCommandService;
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

	/**
	 * Set the default php timezone
	 *
	 * @param $defaultTimezone
	 * @return $this
	 */
	public function setDefaultTimezone( $defaultTimezone ) {
		$this->defaultTimezone = $defaultTimezone;
		return $this;
	}
}