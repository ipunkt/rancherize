<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Infrastructure\InfrastructureWriter;
use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\File\FileWriter;
use Symfony\Component\Console\Input\InputInterface;

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
	 * BuildService constructor.
	 * @param ValidateService $validateService
	 * @param InfrastructureWriter $infrastructureWriter
	 */
	public function __construct(ValidateService $validateService, InfrastructureWriter $infrastructureWriter) {
		$this->validateService = $validateService;
		$this->infrastructureWriter = $infrastructureWriter;
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

		$this->validateService->validate($blueprint, $configuration, $environment);
		$infrastructure = $blueprint->build($configuration, $environment, $this->version);

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
}