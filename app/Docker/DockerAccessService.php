<?php namespace Rancherize\Docker;
use Rancherize\Configuration\Configuration;
use Rancherize\Docker\Exceptions\AccountNotFoundException;

/**
 * Class DockerAccessService
 * @package Rancherize\Docker
 *
 * Reads the DockerAccounts from the configuration
 */
class DockerAccessService {

	/**
	 * @var array
	 */
	protected $accounts = [];

	/**
	 * DockerAccessService constructor.
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration) {
		$this->accounts = $configuration->get('global.docker');
	}

	/**
	 * @return array
	 */
	public function availableAccounts() {
		return array_keys($this->accounts);
	}

	/**
	 * @param string $name
	 * @return DockerAccount
	 */
	public function getAccount(string $name) : DockerAccount {
		if(!array_key_exists($name, $this->accounts))
			throw new AccountNotFoundException($name);

		return new ArrayDockerAccount($this->accounts[$name]);
	}

}