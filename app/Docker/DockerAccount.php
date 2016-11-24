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
	public function getUsername() : string;

	/**
	 * @return string
	 */
	public function getPassword() : string;
}