<?php namespace Rancherize\Blueprint\Infrastructure\Service;
use Rancherize\File\FileWriter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ServiceWriter
 * @package Rancherize\Blueprint\Infrastructure\Service
 */
class ServiceWriter {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * DockerfileWriter constructor.
	 * @param $path
	 */
	public function __construct(string $path) {
		$this->path = $path;
	}

	/**
	 * @param Service $service
	 * @param FileWriter $fileWriter
	 */
	public function write(Service $service, FileWriter $fileWriter) {
		$content = [];

		$this->addNonEmpty('image', $service->getImage(), $content);
		$this->addNonEmpty('tty', $service->isTty(), $content);

		$environment = [];
		foreach($service->getEnvironmentVariables() as $name => $value)
			$environment[$name] = $value;
		$this->addNonEmpty('environment', $environment, $content);

		$volumes = [];
		foreach($service->getVolumes() as $name => $value)
			$volumes[] = "$name:$value";
		$this->addNonEmpty('volumes', $volumes, $content);

		$volumesFrom = [];
		foreach($service->getVolumesFrom() as $name => $value)
			$volumesFrom[] = "$name:$value";
		$this->addNonEmpty('volumes_from', $volumesFrom, $content);

		$yamlContent = Yaml::dump([$service->getName() => $content]);

		$fileWriter->append($this->path.'docker-compose.yml', $yamlContent);
	}

	protected function addNonEmpty($name, $value, &$content) {
		if( !empty($value) )
			$content[$name] = $value;
	}

	public function clear(FileWriter $fileWriter) {
		$fileWriter->put($this->path.'docker-compose.yml', '');
	}
}