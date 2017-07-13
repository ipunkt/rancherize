<?php namespace Rancherize\Docker\Events;

use Rancherize\Configuration\Configuration;
use Rancherize\Docker\ArrayDockerAccount;
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
	 * @var ArrayDockerAccount
	 */
	private $dockerAccount;

	/**
	 * DockerRetrievingAccountEvent constructor.
	 * @param $name
	 * @param array $account
	 * @param ArrayDockerAccount $dockerAccount
	 * @internal param array $allAccounts
	 */
	public function __construct( $name, array $account, ArrayDockerAccount $dockerAccount) {
		$this->name = $name;
		$this->account = $account;
		$this->dockerAccount = $dockerAccount;
	}


}