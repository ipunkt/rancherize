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
	 * @param string $environment
	 * @param InputInterface $input
	 */
	public function build(string $environment, InputInterface $input) {

		$configuration = $this->loadConfiguration();
		$blueprintName = $configuration->get('project.blueprint');
		$blueprint = $this->loadBlueprint($input, $blueprintName);

		$blueprint->validate($configuration, $environment);
		$infrastructure = $blueprint->build($configuration, $environment);

		$infrastructureWriter = new InfrastructureWriter('./.rancherize/');
		$infrastructureWriter->write($infrastructure, new FileWriter());

	}
}