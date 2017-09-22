<?php namespace Rancherize\EnvironmentAccessConfig;
use Rancherize\RancherAccess\ArrayRancherAccount;
use Rancherize\RancherAccess\RancherAccessService;

/**
 * Class RancherAccessConfigService
 * @package Rancherize\Services
 *
 * Provides RancherAccounts from the configuration
 */
class RancherAccessEnvironmentService implements RancherAccessService
{

	/**
	 * @var array
	 */
	private $account = [];

	public function __construct() {
		$this->account = [
			'url' => getenv('RANCHER_URL'),
			'key' => getenv('RANCHER_KEY'),
			'secret' => getenv('RANCHER_SECRET')
		];
	}

	/**
	 * @return string[]
	 */
	public function availableAccounts() {
		return ['default'];
	}

    /**
     * @param string $name
     * @return ArrayRancherAccount
     */
	public function getAccount(string $name) : ArrayRancherAccount {
		return new ArrayRancherAccount($this->account);
	}
}