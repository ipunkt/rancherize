<?php namespace Rancherize\Blueprint\Services\Database\DatabaseBuilder;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Infrastructure\Service\Services\DatabaseService;
use Rancherize\Blueprint\Infrastructure\Service\Services\PmaService;
use Rancherize\Configuration\Configuration;

/**
 * Class DatabaseBuilder
 * @package Rancherize\Blueprint\Services\Database
 */
class DatabaseBuilder {

	/**
	 * @param Configuration $config
	 * @param Service $serverService
	 * @param Infrastructure $infrastructure
	 */
	public function addDatabaseService(Configuration $config, Service $serverService, Infrastructure $infrastructure) {
		if (!$config->get('add-database', false))
			return;

		$databaseService = new DatabaseService();

		if ($config->has('database.name'))
			$databaseService->setDatabaseName($config->get('database.name'));
		if ($config->has('database.user'))
			$databaseService->setDatabaseUser($config->get('database.user'));
		if ($config->has('database.password'))
			$databaseService->setDatabasePassword($config->get('database.password'));

		$this->addDumps($databaseService, $config);

		$serverService->addLink($databaseService, 'database-master');
		$serverService->setEnvironmentVariable('DATABASE_NAME', $databaseService->getDatabaseName());
		$serverService->setEnvironmentVariable('DATABASE_USER', $databaseService->getDatabaseUser());
		$serverService->setEnvironmentVariable('DATABASE_PASSWORD', $databaseService->getDatabasePassword());

		/**
		 * Laravel 5.3 compatibility env vars https://ipunkt-intern.demobereich.de/trac/ticket/217#comment:1
		 */
		$serverService->setEnvironmentVariable('DB_HOST', 'database-master');
		$serverService->setEnvironmentVariable('DB_PORT', 3306);
		$serverService->setEnvironmentVariable('DB_DATABASE', $databaseService->getDatabaseName());
		$serverService->setEnvironmentVariable('DB_USERNAME', $databaseService->getDatabaseUser());
		$serverService->setEnvironmentVariable('DB_PASSWORD', $databaseService->getDatabasePassword());

		$infrastructure->addService($databaseService);


		/**
		 * PMA
		 */
		$pma = $config->get('database.pma', true);
		$isPmaEnabledDirectly = ( !is_array($pma) && $pma == true );
		$isPmaEnabledInArray = ( is_array($pma) && $config->get('database.pma.enable', true) );
		if ( $isPmaEnabledInArray ||  $isPmaEnabledDirectly ) {
			$pmaService = new PmaService();
			$pmaService->addLink($databaseService, 'db');

			if ( !$config->get('database.pma.require-login', false) ) {
				$pmaService->setLogin(
					$databaseService->getDatabaseUser(),
					$databaseService->getDatabasePassword()
				);
			}
			if ($config->get('database.pma-expose', true) || $config->get('database.pma.expose', true)) {
				$legacyPort = $config->get('database.pma-port', 8082);
				$pmaService->expose(80, $config->get('database.pma.port', $legacyPort));

			}

			$infrastructure->addService($pmaService);
		}
	}

	/**
	 * @param Configuration $config
	 * @param DatabaseService $databaseService
	 */
	protected function addDumps(DatabaseService $databaseService, Configuration $config) {
		$dumpKey = 'database.init-dumps';
		if (!$config->has( $dumpKey ))
			return;

		$dumpPathes = $config->get( $dumpKey, [] );

		if( !is_array($dumpPathes) )
			return;

		foreach($dumpPathes as $dumpPath) {
			$databaseService->addInitDumpVolume($dumpPath);
		}
	}

}