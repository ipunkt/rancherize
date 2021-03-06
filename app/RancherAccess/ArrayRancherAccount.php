<?php namespace Rancherize\RancherAccess;

/**
 * Class ArrayRancherAccount
 * @package Rancherize\RancherAccess
 *
 * Provides RancherAccount using an array with the keys Key -> `key` and Secret -> `secret`
 */
class ArrayRancherAccount implements RancherAccount, RancherCliAccount {
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

	/**
	 * @return string
	 */
	public function getComposeVersion(): string {
		return $this->get('compose-version', 'current');
	}

	/**
	 * @param string $key
	 * @param null $default
	 * @return mixed|null
	 */
	private function get(string $key, $default = null) {
		if( !array_key_exists($key, $this->account) )
			return $default;

		return $this->account[$key];
	}

	/**
	 * @return string
	 */
	public function getRancherCompose() : string {
		return $this->get('rancher-compose', 'rancher-compose');
	}


	/**
	 * returns the name of the rancher exectuable, e.g. `rancher`
	 *
	 * @return string
	 */
	public function getCliVersion() {
		return $this->get('rancher-cli', 'rancher');
	}
}