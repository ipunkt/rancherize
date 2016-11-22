<?php namespace Rancherize\RancherAccess;
use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\ArrayRancherAccount;
use Rancherize\RancherAccess\Exceptions\AccountNotFoundException;

/**
 * Class RancherAccessService
 * @package Rancherize\Services
 */
class RancherAccessService {

	/**
	 * @var array
	 */
	private $accounts = [];

	/**
	 * RancherAccessService constructor.
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration) {
		$this->accounts = $configuration->get('global.rancher');
	}

	/**
	 * @return string[]
	 */
	public function availableAccounts() {
		return array_keys($this->accounts);
	}

	/**
	 * @param string $name
	 */
	public function getAccount(string $name) {
		if(! array_key_exists($name, $this->accounts))
			throw new AccountNotFoundException($name);

		return new ArrayRancherAccount($this->accounts[$name]);
	}
}