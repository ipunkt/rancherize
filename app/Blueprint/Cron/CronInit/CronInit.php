<?php namespace Rancherize\Blueprint\Cron\CronInit;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Services\ConfigurationInitializer;

/**
 * Class CronInit
 * @package Rancherize\Blueprint\Cron\CronInit
 */
class CronInit {

	/**
	 * @param Configurable $configurable
	 * @param ConfigurationInitializer $initializer
	 */
	public function init( Configurable $configurable, ConfigurationInitializer $initializer ) {

		$initializer->init($configurable, 'cron', []);
	}
}