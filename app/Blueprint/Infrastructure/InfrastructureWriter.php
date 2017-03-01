<?php namespace Rancherize\Blueprint\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Service\ServiceWriter;
use Rancherize\Blueprint\Infrastructure\Volume\VolumeWriter;
use Rancherize\File\FileWriter;

/**
 * Class InfrastructureWriter
 * @package Rancherize\Blueprint\Infrastructure
 *
 * Use
 */
class InfrastructureWriter {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var bool
	 */
	protected $skipClear = false;
	/**
	 * @var DockerfileWriter
	 */
	private $dockerfileWriter;
	/**
	 * @var ServiceWriter
	 */
	private $serviceWriter;
	/**
	 * @var VolumeWriter
	 */
	private $volumeWriter;

	/**
	 * InfrastructureWriter constructor.
	 * @param DockerfileWriter $dockerfileWriter
	 * @param ServiceWriter $serviceWriter
	 * @param VolumeWriter $volumeWriter
	 */
	public function __construct(DockerfileWriter $dockerfileWriter, ServiceWriter $serviceWriter,
								VolumeWriter $volumeWriter) {
		$this->dockerfileWriter = $dockerfileWriter;
		$this->serviceWriter = $serviceWriter;
		$this->volumeWriter = $volumeWriter;
	}

	/**
	 * @param Infrastructure $infrastructure
	 * @param FileWriter $fileWriter
	 */
	public function write(Infrastructure $infrastructure, FileWriter $fileWriter) {

		$dockerfileWriter = $this->dockerfileWriter;
		$dockerfileWriter->setPath($this->path);
		$dockerfileWriter->write($infrastructure->getDockerfile(), $fileWriter);

		$serviceWriter = $this->serviceWriter;
		$serviceWriter->setPath($this->path);
		$volumeWriter = $this->volumeWriter;
		$volumeWriter->setPath($this->path);

		if( !$this->skipClear )
			$serviceWriter->clear($fileWriter);

		foreach($infrastructure->getServices() as $service)
			$serviceWriter->write($service, $fileWriter);

		foreach($infrastructure->getVolumes() as $volume)
			$volumeWriter->write($volume, $fileWriter);

	}

	/**
	 * @param boolean $skipClear
	 * @return InfrastructureWriter
	 */
	public function setSkipClear(bool $skipClear): InfrastructureWriter {
		$this->skipClear = $skipClear;
		return $this;
	}

	/**
	 * @param string $path
	 * @return $this
	 */
	public function setPath(string $path) {
		$this->path = $path;
		return $this;
	}
}