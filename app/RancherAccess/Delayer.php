<?php namespace Rancherize\RancherAccess;

/**
 * Class Delayer
 * @package Rancherize\RancherAccess
 *
 * Used by RancherService::wait to delay
 */
interface Delayer {

	/**
	 * @param int $run
	 */
	function delay(int $run);
}