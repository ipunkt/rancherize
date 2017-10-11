<?php namespace Rancherize\RancherAccess;

use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\Exceptions\AccountNotFoundInConfigurationException;

/**
 * Class RancherAccessConfigService
 * @package Rancherize\Services
 *
 * Provides RancherAccounts from the configuration
 */
class RancherAccessConfigService implements RancherAccessService, RancherAccessParsesConfiguration
{

	/**
	 * @var array
	 */
	private $accounts = [];

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
			throw new AccountNotFoundInConfigurationException($name);

		return new ArrayRancherAccount($this->accounts[$name]);
	}

	public function parse(Configuration $configuration) {
		$this->accounts = $configuration->get('global.rancher');
	}
}