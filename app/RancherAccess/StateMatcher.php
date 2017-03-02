<?php namespace Rancherize\RancherAccess;

/**
 * Interface StateMatcher
 * @package Rancherize\RancherAccess
 *
 * Decide whether a state matches or not.
 * Used by RancherService::wait to
 */
interface StateMatcher {
	/**
	 * @param $service
	 * @return bool
	 */
	function match($service);
}