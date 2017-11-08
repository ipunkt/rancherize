<?php namespace Rancherize\EnvironmentAccessConfig;
use Rancherize\Configuration\Configuration;
use Rancherize\Docker\ArrayDockerAccount;
use Rancherize\Docker\DockerAccessService;
use Rancherize\Docker\DockerAccount;
use Rancherize\Docker\Events\DockerRetrievingAccountEvent;
use Rancherize\Docker\Exceptions\AccountNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class DockerConfigAccessService
 * @package Rancherize\Docker
 *
 * Reads the DockerAccounts from the configuration
 */
class DockerAccessEnvironmentService implements DockerAccessService
{

    /**
     * @var array
     */
    protected $account = [];
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * DockerConfigAccessService constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array
     */
    public function availableAccounts() {
        return ['default'];
    }

    /**
     * @param string $name
     * @return DockerAccount
     */
    public function getAccount(string $name) : DockerAccount {
        $dockerAccount = new ArrayDockerAccount($this->account);

        $retrievingEvent = new DockerRetrievingAccountEvent($name, $this->account, $dockerAccount);

        $this->eventDispatcher->dispatch(DockerRetrievingAccountEvent::NAME, $retrievingEvent);
        $dockerAccount = $retrievingEvent->getDockerAccount();

        return $dockerAccount;
    }

    /**
     * @param $configuration
     */
    public function parse( Configuration $configuration ) {
        $this->account = [
            'user' => getenv('DOCKER_USER'),
            'server' => getenv('DOCKER_SERVER') ?: '',
            'password' => getenv('DOCKER_PASSWORD'),
            'ecr' => getenv('DOCKER_ECR') ?: false
        ];
    }

}
