<?php namespace Rancherize\RancherAccess;

/**
 * Interface RancherAccount
 * @package Rancherize\RancherAccess
 *
 * Represents access to docker
 */
interface RancherAccount {
	/**
	 * The url to access the server api
	 *
	 * @return string
	 */
	function getUrl();

	/**
	 * The api key aka username
	 *
	 * @return string
	 */
	function getKey();

	/**
	 * The secret key aka the password
	 *
	 * @return string
	 */
	function getSecret();
}