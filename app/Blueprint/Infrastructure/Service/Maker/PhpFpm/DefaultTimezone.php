<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

/**
 * Interface DefaultTimezone
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 *
 * Possibly implemented by a PhpVersion.
 * Sets the default timezone used by php
 */
interface DefaultTimezone {

	const DEFAULT_TIMEZONE = 'default';

	/**
	 * Set the default php timezone
	 *
	 * @param $defaultTimezone
	 * @return $this
	 */
	function setDefaultTimezone( $defaultTimezone );

}