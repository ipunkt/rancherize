<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\MailTarget;
use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Configurations\UpdatesBackendEnvironment;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class PhpFpmMaker
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker
 */
class PhpFpmMaker {

	/**
	 * @var PhpVersion[]
	 */
	protected $phpVersions = [];

	/**
	 * @var int
	 */
	protected $defaultVersion = null;

	/**
	 * @var string|Service
	 */
	private $appTarget;

	/**
	 * @param string $hostDirectory
	 * @param string $containerDirectory
	 */
	public function setAppMount(string $hostDirectory, string $containerDirectory) {
		$this->appTarget = [$hostDirectory, $containerDirectory];
	}

	/**
	 * @param Service $service
	 */
	public function setAppService(Service $service) {
		$this->appTarget = $service;
	}

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		$phpVersion = $this->getPhpVersion( $config );

		$phpVersion->make($config, $mainService, $infrastructure, function(Service $service) {
			$this->addAppSource($service);
		});
	}

	/**
	 * @param $commandName
	 * @param $command
	 * @param Service $mainService
	 * @param Configuration $configuration
	 * @return Service
	 */
	public function makeCommand( $commandName, $command, Service $mainService, Configuration $configuration ) {

		$phpVersion = $this->getPhpVersion( $configuration );

		$commandService =  $phpVersion->makeCommand( $commandName, $command, $mainService);
		$this->addAppSource($commandService);
		$mainService->addSidekick($commandService);

		return $commandService;
	}

	/**
	 * @param string $commandName
	 * @param string $command
	 * @param Service $mainService
	 * @param Configuration $configuration
	 * @return Service
	 */
	public function makeService( string $commandName, string $command, Service $mainService, Configuration $configuration ) {
		$phpVersion = $this->getPhpVersion( $configuration );

		$commandService =  $phpVersion->makeCommand( $commandName, $command, $mainService);
		$this->addAppSource($commandService);

		return $commandService;
	}

	/**
	 * Add a PhPVersion.
	 * The version will be made default if it is the first version set or if the $default parameter is set to true
	 *
	 * @param PhpVersion $version
	 * @return $this
	 */
	public function addVersion(PhpVersion $version) {
		$versionString = $version->getVersion();

		$this->phpVersions[$versionString] = $version;

		return $this;
	}

	/**
	 * @param Configuration $config
	 * @return PhpVersion
	 */
	protected function getPhpVersion( Configuration $config ): PhpVersion {
		if ( empty( $this->phpVersions ) )
			throw new NoPhpVersionsAvailableException;

		$phpVersionString = $config->get( 'php', '7.0' );

		$advancedConfig = is_array($phpVersionString);
		if( $advancedConfig )
			$phpVersionString = $config->get('php.version', '7.0');

		if ( !array_key_exists( $phpVersionString, $this->phpVersions ) )
			throw new PhpVersionNotAvailableException( $phpVersionString );

		$phpVersion = $this->phpVersions[$phpVersionString];

		$this->setConfig($phpVersion, $config);

		return $phpVersion;
	}

	/**
	 * @param PhpVersion $phpVersion
	 * @param $config
	 */
	private function setConfig( PhpVersion $phpVersion, Configuration $config ) {

		if ( !is_array( $config->get( 'php' ) ) ) {

			if ( $phpVersion instanceof UpdatesBackendEnvironment )
				$phpVersion->enableUpdateEnvironment( true );

			return;
		}

		$phpConfig = new PrefixConfigurationDecorator($config, 'php.');
		if( $phpVersion instanceof MemoryLimit && $phpConfig->has('memory-limit')  )
			$phpVersion->setMemoryLimit( $phpConfig->get('memory-limit') );

		if( $phpVersion instanceof PostLimit && $phpConfig->has('post-limit')  )
			$phpVersion->setPostLimit( $phpConfig->get('post-limit') );

		if( $phpVersion instanceof UploadFileLimit && $phpConfig->has('upload-file-limit')  )
			$phpVersion->setUploadFileLimit( $phpConfig->get('upload-file-limit') );

		if( $phpVersion instanceof DefaultTimezone && $phpConfig->has('default-timezone')  )
			$phpVersion->setDefaultTimezone( $phpConfig->get('default-timezone') );

		if( $phpVersion instanceof MailTarget && $phpConfig->has('mail.host')  )
			$phpVersion->setMailHost( $phpConfig->get('mail.host', 'mail') );

		if( $phpVersion instanceof MailTarget && $phpConfig->has('mail.port')  )
			$phpVersion->setMailPort( $phpConfig->get('mail.port', 'mail') );

		if( $phpVersion instanceof MailTarget && $phpConfig->has('mail.auth')  ) {
			$phpVersion->setMailAuthentication( $phpConfig->get('mail.auth') );
			$phpVersion->setMailUsername( $phpConfig->get('mail.username', 'smtp') );
			$phpVersion->setMailPassword( $phpConfig->get('mail.password', 'smtp') );

		}

		if( $phpVersion instanceof DebugImage ) {
			$phpVersion->setDebug( $phpConfig->get('debug', false) );
			$phpVersion->setDebugListener( $phpConfig->get('debug-listener', null) );
		}

		if( $phpVersion instanceof UpdatesBackendEnvironment )
			$phpVersion->enableUpdateEnvironment( $phpConfig->get('update-backend', true) );
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
	 * @param $phpFpmService
	 */
	protected function copyAppSource(Service $phpFpmService) {
		$appTarget = $this->appTarget;

		if ($appTarget instanceof Service) {
			$phpFpmService->addVolumeFrom($appTarget);
			return;
		}

		list($hostDirectory, $containerDirectory) = $appTarget;
		$phpFpmService->addVolume($hostDirectory, $containerDirectory);
	}

}