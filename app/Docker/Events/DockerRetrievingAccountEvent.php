<?php namespace Rancherize\Docker\Events;

use Rancherize\Configuration\Configuration;
use Rancherize\Docker\DockerAccount;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DockerRetrievingAccountEvent
 * @package Rancherize\Docker\Events
 */
class DockerRetrievingAccountEvent extends Event {

	const NAME = 'docker.account.retrieving';

	/**
	 * @var Configuration
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $account;

	/**
	 * @var DockerAccount
	 */
	private $dockerAccount;

	/**
	 * DockerRetrievingAccountEvent constructor.
	 * @param $name
	 * @param array $account
	 * @param DockerAccount $dockerAccount
	 * @internal param array $allAccounts
	 */
	public function __construct( $name, array $account, DockerAccount $dockerAccount) {
		$this->name = $name;
		$this->account = $account;
		$this->dockerAccount = $dockerAccount;
	}

	/**
	 * @return Configuration
	 */
	public function getName(): Configuration {
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getAccount(): array {
		return $this->account;
	}

	/**
	 * @return DockerAccount
	 */
	public function getDockerAccount(): DockerAccount {
		return $this->dockerAccount;
	}

	/**
	 * @param DockerAccount $dockerAccount
	 * @return $this
	 */
	public function setDockerAccount( DockerAccount $dockerAccount ) {
		$this->dockerAccount = $dockerAccount;
		return $this;
	}

}