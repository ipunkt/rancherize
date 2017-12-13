<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\AlpineDebugImageBuilder;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\MailTarget;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\DebugImage;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\DefaultTimezone;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\MemoryLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersion;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PostLimit;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\DebugImageTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\DefaultTimezoneTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\MailTargetTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\MemoryLimitTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\PostLimitTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\UploadFileLimitTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\UploadFileLimit;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP70
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP70 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit, DefaultTimezone, MailTarget, DebugImage {

	const PHP_IMAGE = 'ipunktbs/php:7.0-fpm';

	use DebugImageTrait;
	use MailTargetTrait;
	use DefaultTimezoneTrait;
	use UploadFileLimitTrait;
	use PostLimitTrait;
	use MemoryLimitTrait;

	/**
	 * @var string|Service
	 */
	protected $appTarget;
	/**
	 * @var AlpineDebugImageBuilder
	 */
	private $debugImageBuilder;

	/**
	 * PHP70 constructor.
	 * @param AlpineDebugImageBuilder $debugImageBuilder
	 */
	public function __construct( AlpineDebugImageBuilder $debugImageBuilder) {
		$this->debugImageBuilder = $debugImageBuilder;
	}

	public function make( Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		$phpFpmService = new Service();
		$phpFpmService->setName($mainService->getName().'-PHP-FPM');

		$this->setImage($phpFpmService);

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

		$mailHost = $this->mailHost;
		$mailPort = $this->mailPort;

		if($mailHost !== null && $mailPort !== null)
			$mailHost .= ':'.$mailPort;

		if($mailHost !== null)
			$phpFpmService->setEnvironmentVariable('SMTP_SERVER', $mailHost.':'.$mailPort);

		$mailAuth = $this->mailAuthentication;
		if($mailAuth !== null)
			$phpFpmService->setEnvironmentVariable('SMTP_AUTHENTICATION', $mailAuth);

		$mailUsername = $this->mailUsername;
		if($mailUsername !== null)
			$phpFpmService->setEnvironmentVariable('SMTP_USER', $mailUsername);

		$mailPassword = $this->mailPassword;
		if($mailPassword !== null)
			$phpFpmService->setEnvironmentVariable('SMTP_PASSWORD', $mailPassword);

		$this->addAppSource($phpFpmService);

		/**
		 * Copy environment variables because environment variables are expected to be available in php
		 */
		foreach( $mainService->getEnvironmentVariables() as $name => $value )
			$phpFpmService->setEnvironmentVariable($name, $value);

		$mainService->addLink($phpFpmService, 'phpfpm');

		/**
		 * Copy links from the main service so databases etc are available
		 */
		$phpFpmService->addLinksFrom($mainService);

		$mainService->addSidekick($phpFpmService);
		$infrastructure->addService($phpFpmService);
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
	 * @param $commandName
	 * @param $command
	 * @param Service $mainService
	 * @return Service
	 */
	public function makeCommand( $commandName, $command, Service $mainService) {

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

	protected function setImage(Service $service) {
		$image = self::PHP_IMAGE;
		if( $this->debug )
			$image = $this->debugImageBuilder->makeImage(self::PHP_IMAGE );

		$service->setImage( $image );
	}
 }