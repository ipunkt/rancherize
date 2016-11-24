<?php namespace Rancherize\RancherAccess;

/**
 * Class ArrayRancherAccount
 * @package Rancherize\RancherAccess
 *
 * Provides RancherAccount using an array with the keys Key -> `key` and Secret -> `secret`
 */
class ArrayRancherAccount implements RancherAccount {
	/**
	 * @var array
	 */
	private $account;

	/**
	 * ArrayRancherAccount constructor.
	 * @param array $account
	 */
	public function __construct(array $account) {
		$this->account = $account;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->get('url');
	}

	/**
	 * @return string
	 */
	public function getKey() {
		return $this->get('key');
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->get('secret');
	}

	private function get($key) {
		if( !array_key_exists($key, $this->account) )
			return null;

		return $this->account[$key];
	}
}