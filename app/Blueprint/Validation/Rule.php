<?php namespace Rancherize\Blueprint\Validation;

use Rancherize\Configuration\Configuration;

/**
 * Interface Rule
 * @package Rancherize\Blueprint\Validation
 */
interface Rule {

	/**
	 * @param Configuration $configuration
	 * @return mixed
	 */
	function validate(Configuration $configuration);
}