<?php namespace Rancherize\Blueprint;

use Rancherize\Docker\DockerAccount;

/**
 * Interface TakesDockerAccount
 * @package Rancherize\Blueprint
 */
interface TakesDockerAccount {

	/**
	 * @param DockerAccount $account
	 * @return $this
	 */
	function setDockerAccount(DockerAccount $account);

}