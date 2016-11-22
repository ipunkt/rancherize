<?php namespace Rancherize\Blueprint\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Service\ServiceWriter;
use Rancherize\File\FileWriter;

/**
 * Class InfrastructureWriter
 * @package Rancherize\Blueprint\Infrastructure
 */
class InfrastructureWriter {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * InfrastructureWriter constructor.
	 * @param string $path
	 */
	public function __construct(string $path) {
		$this->path = $path;
	}

	/**
	 * @param Infrastructure $infrastructure
	 * @param FileWriter $fileWriter
	 */
	public function write(Infrastructure $infrastructure, FileWriter $fileWriter) {

		$dockerfileWriter = new DockerfileWriter($this->path);
		$dockerfileWriter->write($infrastructure->getDockerfile(), $fileWriter);

		$serviceWriter = new ServiceWriter($this->path);
		$serviceWriter->clear($fileWriter);

		foreach($infrastructure->getServices() as $service)
			$serviceWriter->write($service, $fileWriter);

	}
}