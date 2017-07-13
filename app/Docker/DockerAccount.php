<?php namespace Rancherize\Docker;

/**
 * Interface DockerAccount
 * @package Rancherize\Docker
 *
 * Data store representing a docker account
 */
interface DockerAccount {

	/**
	 * @return string
	 */
	function getUsername() : string;

	/**
	 * @return string
	 */
	function getPassword() : string;

	/**
	 * @return string|null
	 */
	function getServer();
}