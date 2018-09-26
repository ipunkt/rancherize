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
		$capitalName = strtoupper($name);
		if( !empty( getenv('RANCHER_'.$capitalName.'_URL') ) )
			return new ArrayRancherAccount([
				'url' => getenv('RANCHER_'.$capitalName.'_URL'),
				'key' => getenv('RANCHER_'.$capitalName.'_KEY'),
				'secret' => getenv('RANCHER_'.$capitalName.'_SECRET')
			]);

		return new ArrayRancherAccount($this->account);
	}
}