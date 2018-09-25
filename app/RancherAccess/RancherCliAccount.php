<?php namespace Rancherize\RancherAccess;

/**
 * Interface RancherCliAccount
 * @package Rancherize\RancherAccess
 */
interface RancherCliAccount {


	/**
	 * returns the name of the rancher exectuable, e.g. `rancher`
	 *
	 * @return string
	 */
	function getCliVersion();

}