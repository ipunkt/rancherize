<?php namespace Rancherize\Blueprint;
use Rancherize\Configuration\Configurable;

/**
 * Interface Blueprint
 * @package Rancherize\Blueprint
 */
interface Blueprint {

	/**
	 * @param Configurable $configurable
	 * @return
	 */
	function init(Configurable $configurable);
}