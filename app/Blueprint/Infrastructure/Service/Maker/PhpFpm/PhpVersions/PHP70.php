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
class PHP70 implements PhpVersion, MemoryLimit, PostLimit, UploadFileLimit, DefaultTimezone, MailTarget {

	const PHP_IMAGE = 'ipunktbs/php:7.0-fpm';

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

	public function make( Configuration $config, Service $mainService, Infrastructure $infrastructure) {
		/**
		 * PHP7.0 is started by default, no fpm service needs to be added
		 */

		$memoryLimit = $this->memoryLimit;
		if( $memoryLimit !== self::DEFAULT_MEMORY_LIMIT)
			$mainService->setEnvironmentVariable('PHP_MEMORY_LIMIT', $memoryLimit);

		$postLimit = $this->postLimit;
		if( $postLimit !== self::DEFAULT_POST_LIMIT)
			$mainService->setEnvironmentVariable('PHP_POST_MAX_SIZE', $postLimit);

		$uploadFileLimit = $this->uploadFileLimit;
		if( $uploadFileLimit !== self::DEFAULT_UPLOAD_FILE_LIMIT)
			$mainService->setEnvironmentVariable('PHP_UPLOAD_MAX_FILESIZE', $uploadFileLimit);
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