<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\MailTarget;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\DefaultTimezone;
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
class PHP70 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit, DefaultTimezone, MailTarget {

	const PHP_IMAGE = 'ipunktbs/php:7.0-fpm-dev';

	/**
	 * @var string|Service
	 */
	protected $appTarget;

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

	/**
	 * @var string
	 */
	protected $defaultTimezone = self::DEFAULT_TIMEZONE;
	/**
	 * @var string
	 */
	private $mailHost;

	/**
	 * @var int
	 */
	private $mailPort;

	/**
	 * @var string
	 */
	private $mailUsername;

	/**
	 * @var string
	 */
	private $mailPassword;

	/**
	 * @var string
	 */
	private $mailAuthentication;

	public function make( Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		$phpFpmService = new Service();
		$phpFpmService->setName($mainService->getName().'-PHP-FPM');
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

	/**
	 * @param string $host
	 */
	public function setMailHost( string $host ) {
		$this->mailHost = $host;
	}

	/**
	 * @param int $port
	 */
	public function setMailPort( int $port ) {
		$this->mailPort = $port;
	}

	/**
	 * @param string $username
	 */
	public function setMailUsername( string $username ) {
		$this->mailUsername = $username;
	}

	/**
	 * @param string $password
	 */
	public function setMailPassword( string $password ) {
		$this->mailPassword = $password;
	}

	/**
	 * @param string $authMethod
	 */
	public function setMailAuthentication( string $authMethod ) {
		$this->mailAuthentication = $authMethod;
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