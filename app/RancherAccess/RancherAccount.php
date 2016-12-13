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

	/**
	 * Returns a string version identifier for the used rancher-compose version.
	 * Known versions:
	 * `current` - use most recent behaviour
	 * `0.9` - Use full v1 Url
	 * `0.10` - Use Domain only url
	 *
	 *
	 * @return string
	 */
	function getComposeVersion() : string;

	/**
	 * @return string
	 */
	function getRancherCompose() : string;
}