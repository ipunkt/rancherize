<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;

use Closure;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\AlpineDebugImageBuilder;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\MailTarget;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\UpdatesBackendEnvironment;
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
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\UpdatesBackendEnvironmentTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits\UploadFileLimitTrait;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\UploadFileLimit;
use Rancherize\Blueprint\Infrastructure\Service\NetworkMode\ShareNetworkMode;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class PHP70
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions
 */
class PHP70 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit, DefaultTimezone, MailTarget, DebugImage, UpdatesBackendEnvironment {

	const PHP_IMAGE = 'ipunktbs/php:7.0-fpm';

	use DebugImageTrait;
	use MailTargetTrait;
	use DefaultTimezoneTrait;
	use UploadFileLimitTrait;
	use PostLimitTrait;
	use MemoryLimitTrait;
	use UpdatesBackendEnvironmentTrait;

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

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 * @param Closure|null $customize
	 */
	public function make( Configuration $config, Service $mainService, Infrastructure $infrastructure, Closure $customize = null) {
		if($customize === null)
			$customize = function(Service $service) {};

		$phpFpmService = new Service();
		$phpFpmService->setNetworkMode( new ShareNetworkMode( $mainService ) );
		$mainService->setEnvironmentVariable( 'BACKEND_HOST', '127.0.0.1:9000' );
		$phpFpmService->setName( function() use ($mainService) {
			$name =  'PHP-FPM-'.$mainService->getName();
			return $name;
		});

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

		$phpFpmService->setEnvironmentVariablesCallback(function() use ($mainService) {
			return $mainService->getEnvironmentVariables();
		});

		$mainService->addSidekick($phpFpmService);
		$customize($phpFpmService);
		$infrastructure->addService($phpFpmService);
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return '7.0';
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
		$phpCommandService->setName( function() use ($mainService, $commandName) {
			return  'PHP-'.$commandName.'-'.$mainService->getName();
		});
		$this->setImage( $phpCommandService );
		$phpCommandService->setRestart(Service::RESTART_START_ONCE);

		/**
		 * Copy links from the main service so databases etc are available
		 */
		$phpCommandService->addLinksFrom($mainService);

		$phpCommandService->setEnvironmentVariablesCallback(function() use ($mainService) {
			return $mainService->getEnvironmentVariables();
		});

		return $phpCommandService;
	}

	protected function setImage(Service $service) {
		$image = self::PHP_IMAGE;
		if( $this->debug ) {
			$image = $this->debugImageBuilder->makeImage(self::PHP_IMAGE );
			$service->setEnvironmentVariable('XDEBUG_REMOTE_HOST', gethostname());
			if($this->debugListener !== null)
				$service->setEnvironmentVariable('XDEBUG_REMOTE_HOST', $this->debugListener);
		}

		$service->setImage( $image );
	}
 }