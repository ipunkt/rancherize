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
	 * BuildService constructor.
	 * @param ValidateService $validateService
	 */
	public function __construct(ValidateService $validateService) {
		$this->validateService = $validateService;
	}

	/**
	 * @param Blueprint $blueprint
	 * @param Configuration $configuration
	 * @param string $environment
	 * @param bool $skipClear
	 * @internal param InputInterface $input
	 */
	public function build(Blueprint $blueprint, Configuration $configuration, string $environment, $skipClear = false) {

		$this->validateService->validate($blueprint, $configuration, $environment);
		$infrastructure = $blueprint->build($configuration, $environment, $this->version);

		$directory = './.rancherize/';
		if( file_exists($directory) )
			mkdir($directory);

		$infrastructureWriter = new InfrastructureWriter($directory);
		$infrastructureWriter->setSkipClear($skipClear);
		$infrastructureWriter->write($infrastructure, new FileWriter());

	}

	/**
	 * @param $composerConfig
	 */
	public function createDockerCompose($composerConfig) {
		$fileWriter = new FileWriter();
		$fileWriter->put('./.rancherize/docker-compose.yml', $composerConfig);
	}

	/**
	 * @param $rancherConfig
	 */
	public function createRancherCompose($rancherConfig) {
		$fileWriter = new FileWriter();
		$fileWriter->put('./.rancherize/rancher-compose.yml', $rancherConfig);
	}

	/**
	 * @param string $version
	 * @return $this
	 */
	public function setVersion(string $version) {
		$this->version = $version;
		return $this;
	}
}