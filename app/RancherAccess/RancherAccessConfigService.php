<?php namespace Rancherize\RancherAccess;
use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\ArrayRancherAccount;
use Rancherize\RancherAccess\Exceptions\AccountNotFoundException;

/**
 * Class RancherAccessConfigService
 * @package Rancherize\Services
 *
 * Provides RancherAccounts from the configuration
 */
class RancherAccessConfigService implements RancherAccessService
{

	/**
	 * @var array
	 */
	private $accounts = [];

	/**
	 * RancherAccessConfigService constructor.
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
     * @return ArrayRancherAccount
     */
	public function getAccount(string $name) : ArrayRancherAccount {
		if(! array_key_exists($name, $this->accounts))
			throw new AccountNotFoundException($name);

		return new ArrayRancherAccount($this->accounts[$name]);
	}
}