<?php namespace Rancherize\Docker;

/**
 * Class ArrayDockerAccount
 * @package Rancherize\Docker
 *
 * Provides a docker account from an array using the 'user' and 'password' keys
 */
class ArrayDockerAccount implements DockerAccount {
	/**
	 * @var string[]
	 */
	private $account;

	/**
	 * ArrayDockerAccount constructor.
	 * @param array $account
	 */
	public function __construct(array $account) {
		$this->account = $account;
	}

	/**
	 * @return string
	 */
	public function getUsername() : string {
		return $this->get('user');
	}

	/**
	 * @return string
	 */
	public function getPassword() : string {
		return $this->get('password');
	}

	/**
	 *
	 */
	private function get($key) {
		if(!array_key_exists($key, $this->account))
			return '';
		return $this->account[$key];
	}
}