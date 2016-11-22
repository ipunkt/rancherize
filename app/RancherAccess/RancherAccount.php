<?php namespace Rancherize\RancherAccess;

/**
 * Interface RancherAccount
 * @package Rancherize\RancherAccess
 */
interface RancherAccount {
	/**
	 * @return string
	 */
	function getUrl();

	/**
	 * @return string
	 */
	function getKey();

	/**
	 * @return string
	 */
	function getSecret();
}