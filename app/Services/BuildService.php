<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Infrastructure\InfrastructureWriter;
use Rancherize\Blueprint\TakesDockerAccount;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccount;
use Rancherize\File\FileWriter;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class BuildService
 * @package Rancherize\Services
 *
 * Builds an environment by its configuration
 */
class BuildService {

	use LoadsConfigurationTrait;
	use BlueprintTrait;

	/**
	 * @var string
	 */
	protected $version;
	/**
	 * @var ValidateService
	 */
	private $validateService;
	/**
	 * @var InfrastructureWriter
	 */
	private $infrastructureWriter;
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * @var DockerAccount
	 */
	protected $dockerAccount;

	/**
	 * BuildService constructor.
	 * @param ValidateService $validateService
	 * @param InfrastructureWriter $infrastructureWriter
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(ValidateService $validateService, InfrastructureWriter $infrastructureWriter,
		EventDispatcher $eventDispatcher) {
		$this->validateService = $validateService;
		$this->infrastructureWriter = $infrastructureWriter;
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @param Blueprint $blueprint
	 * @param Configuration $configuration
	 * @param string $environment
	 * @param bool $skipClear
	 * @return \Rancherize\Blueprint\Infrastructure\Infrastructure
	 */
	public function build(Blueprint $blueprint, Configuration $configuration, string $environment, $skipClear = false) {

		$directory = $this->createTemporaryDirectory();

		$dockerAccount = $this->dockerAccount;
		if( $this->dockerAccount !== null && $blueprint instanceof TakesDockerAccount )
			$blueprint->setDockerAccount($dockerAccount);

		$this->validateService->validate($blueprint, $configuration, $environment);
		$infrastructure = $blueprint->build($configuration, $environment, $this->version);

		$infrastructureBuiltEvent = new InfrastructureBuiltEvent($infrastructure);
		$this->eventDispatcher->dispatch(InfrastructureBuiltEvent::NAME, $infrastructureBuiltEvent);
		$infrastructure = $infrastructureBuiltEvent->getInfrastructure();

		$infrastructureWriter = $this->infrastructureWriter;
		$infrastructureWriter->setPath($directory);
		$infrastructureWriter->setSkipClear($skipClear);
		$infrastructureWriter->write($infrastructure, new FileWriter());

		return $infrastructure;

	}

	/**
	 * @param $composerConfig
	 */
	public function createDockerCompose($composerConfig) {

		$directory = $this->createTemporaryDirectory();

		$fileWriter = new FileWriter();
		$fileWriter->put($directory.'/docker-compose.yml', $composerConfig);
	}

	/**
	 * @param $rancherConfig
	 */
	public function createRancherCompose($rancherConfig) {

		$directory = $this->createTemporaryDirectory();

		$fileWriter = new FileWriter();
		$fileWriter->put($directory.'/rancher-compose.yml', $rancherConfig);
	}

	/**
	 * @param string $version
	 * @return $this
	 */
	public function setVersion(string $version) {
		$this->version = $version;
		return $this;
	}

	/**
	 * @return string
	 */
	protected function createTemporaryDirectory(): string {
		$directory = './.rancherize/';

		if (!file_exists($directory))
			mkdir($directory);

		return $directory;
	}

	/**
	 * @param DockerAccount $dockerAccount
	 * @return BuildService
	 */
	public function setDockerAccount( DockerAccount $dockerAccount ): BuildService {
		$this->dockerAccount = $dockerAccount;
		return $this;
	}
}