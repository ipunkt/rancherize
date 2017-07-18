<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Services\PathService\Exceptions\PathException;
use Rancherize\Services\PathService\PathService;

/**
 * Class DatabaseService
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 *
 * @TODO: Move to Blueprint/Services/Database/Services
 */
class DatabaseService extends Service {

	/**
	 * DatabaseService constructor.
	 */
	public function __construct() {
		$this->setName('Database');
		$this->setRestart(self::RESTART_UNLESS_STOPPED);
		$this->setTty(true);
		$this->setImage('ipunktbs/mysql-master:v1');
		$this->setKeepStdin(true);

		$this->setEnvironmentVariable('MYSQL_ROOT_PASSWORD', 'root');
		$this->setEnvironmentVariable('REPLICATION_USER', 'replicationuser');
		$this->setEnvironmentVariable('REPLICATION_PASSWORD', 'nothing');
		$this->setEnvironmentVariable('DATABASE', 'db');
		$this->setEnvironmentVariable('USER', 'user');
		$this->setEnvironmentVariable('PASSWORD', 'pw');

	}

	/**
	 * @param $name
	 */
	public function setDatabaseName(string $name) {
		$this->setEnvironmentVariable('DATABASE', $name);
	}

	/**
	 * @param string $user
	 */
	public function setDatabaseUser(string $user) {
		$this->setEnvironmentVariable('USER', $user);
	}

	/**
	 * @param string $password
	 */
	public function setDatabasePassword(string $password) {
		$this->setEnvironmentVariable('PASSWORD', $password);
	}

	/**
	 * @param $name
	 */
	public function getDatabaseName() {
		return $this->getEnvironmentVariable('DATABASE', '');
	}

	/**
	 * @param string $user
	 */
	public function getDatabaseUser() {
		return $this->getEnvironmentVariable('USER', '');
	}

	/**
	 * @param string $password
	 */
	public function getDatabasePassword() {
		return $this->getEnvironmentVariable('PASSWORD', '');
	}

	/**
	 * @param string $path
	 */
	public function addInitDumpVolume($path) {

		/**
		 * @var PathService $pathService
		 */
		$pathService = container('path-service');

		try {
			$pathInfo = $pathService->parsePath($path);
		} catch(PathException $p) {
			return;
		}

		$this->addVolume(getcwd().$pathInfo->getPath(), '/docker-entrypoint-initdb.d/'.$pathInfo->getFilename());
	}
}