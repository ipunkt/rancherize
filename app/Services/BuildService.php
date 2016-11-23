<?php namespace Rancherize\Services;
use Rancherize\Blueprint\Infrastructure\InfrastructureWriter;
use Rancherize\Blueprint\Traits\LoadsBlueprintTrait;
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
	use LoadsBlueprintTrait;

	/**
	 * @var string
	 */
	protected $image;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @param string $environment
	 * @param InputInterface $input
	 * @param bool $skipClear
	 */
	public function build(string $environment, InputInterface $input, $skipClear = false) {

		$configuration = $this->loadConfiguration();
		$blueprintName = $configuration->get('project.blueprint');
		$blueprint = $this->loadBlueprint($input, $blueprintName);

		$blueprint->validate($configuration, $environment);
		$infrastructure = $blueprint->build($configuration, $environment, $this->image, $this->version);

		$infrastructureWriter = new InfrastructureWriter('./.rancherize/');
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
	 * @param string $image
	 * @return $this
	 */
	public function setImage(string $image) {
		$this->image = $image;
		return $this;
	}

	/**
	 * @param string $version
	 */
	public function setVersion(string $version) {
		$this->version = $version;
		return $this;
	}
}