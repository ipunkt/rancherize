<?php namespace Rancherize\Docker;
use Rancherize\Configuration\Configuration;
use Rancherize\Docker\Events\DockerRetrievingAccountEvent;
use Rancherize\Docker\Exceptions\AccountNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class DockerAccessConfigService
 * @package Rancherize\Docker
 *
 * Reads the DockerAccounts from the configuration
 */
class DockerAccessConfigService implements DockerAccessService
{

	/**
	 * @var array
	 */
	protected $accounts = [];
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * DockerAccessConfigService constructor.
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

		$this->eventDispatcher->dispatch(DockerRetrievingAccountEvent::NAME, $retrievingEvent);
		$dockerAccount = $retrievingEvent->getDockerAccount();

		return $dockerAccount;
	}

	/**
	 * @param $configuration
	 */
	public function parse( Configuration $configuration ) {
		$this->accounts = $configuration->get('global.docker');
	}

}