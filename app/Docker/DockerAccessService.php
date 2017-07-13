<?php namespace Rancherize\Docker;
use Rancherize\Configuration\Configuration;
use Rancherize\Docker\Events\DockerRetrievingAccountEvent;
use Rancherize\Docker\Exceptions\AccountNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * DockerAccessService constructor.
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(EventDispatcher $eventDispatcher) {
		$this->eventDispatcher = $eventDispatcher;
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

		$dockerAccount = new ArrayDockerAccount($this->accounts[$name]);
		$retrievingEvent = new DockerRetrievingAccountEvent($name, $this->accounts[$name], $dockerAccount);
		$dockerAccount = $retrievingEvent->getDockerAccount();

		$this->eventDispatcher->dispatch(DockerRetrievingAccountEvent::NAME, $retrievingEvent);

		return $dockerAccount;
	}

	/**
	 * @param $configuration
	 */
	public function parse( Configuration $configuration ) {
		$this->accounts = $configuration->get('global.docker');
	}

}