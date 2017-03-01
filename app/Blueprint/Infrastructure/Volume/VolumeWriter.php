<?php namespace Rancherize\Blueprint\Infrastructure\Volume;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\File\FileLoader;
use Rancherize\File\FileWriter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ServiceWriter
 * @package Rancherize\Blueprint\Infrastructure\Service
 *
 * Add services to docker-compose.yml files
 */
class VolumeWriter {
	/**
	 * @var FileLoader
	 */
	private $fileLoader;

	/**
	 * ServiceWriter constructor.
	 * @param FileLoader $fileLoader
	 * @internal param FileLoader $loader
	 */
	public function __construct(FileLoader $fileLoader) {
		$this->fileLoader = $fileLoader;
	}

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @param Volume $volume
	 * @param FileWriter $fileWriter
	 *
	 * Append the given service to the path/docker-compose.yml and path/rancher-compose.yml
	 */
	public function write(Volume $volume, FileWriter $fileWriter) {
		$content = [];

		$this->addNonEmpty('driver', $volume->getDriver(), $content);

		$this->writeYaml($this->path . 'rancher-compose.yml', $volume, $fileWriter, $content);
	}

	/**
	 * Only add the given option if the value is not empty
	 *
	 * @param $name
	 * @param $value
	 * @param $content
	 */
	protected function addNonEmpty($name, $value, &$content) {
		if( !empty($value) )
			$content[$name] = $value;
	}

	/**
	 * Clear the written files.
	 * Necessary because the write function appends to them so if fresh files are expected they have to be cleared first
	 *
	 * @param FileWriter $fileWriter
	 */
	public function clear(FileWriter $fileWriter) {
		$fileWriter->put($this->path.'docker-compose.yml', '');
		$fileWriter->put($this->path.'rancher-compose.yml', '');
	}

	/**
	 * Set a path to prefix before the *-compose files
	 *
	 * @param string $path
	 * @return VolumeWriter
	 */
	public function setPath(string $path): VolumeWriter {
		$this->path = $path;
		return $this;
	}

	/**
	 * @param Volume $volume
	 * @param FileWriter $fileWriter
	 * @param $content
	 */
	protected function writeYaml($targetFile, Volume $volume, FileWriter $fileWriter, $content) {

		try {

			$dockerData = Yaml::parse($this->fileLoader->get($targetFile));
			if(!is_array($dockerData))
				$dockerData = [];

			// force v2 format because v1 does not support volumes
			if ( !array_key_exists('version', $dockerData)) {
				$services = $dockerData;
				$dockerData = [];
				$dockerData['version'] = '2';
				$dockerData['services'] = $services;
			}

			if(!array_key_exists('volumes', $dockerData))
				$dockerData['volumes'] = [];

			$dockerData['volumes'][$volume->getName()] = $content;

		} catch (FileNotFoundException $e) {
			$dockerData = [
				'version' => '2',
				'services' => [],
				'volumes' => [
					$volume->getName() => $content
				]
			];
		}

		$dockerYaml = Yaml::dump($dockerData, 100, 2);

		$fileWriter->put($targetFile, $dockerYaml);
	}
}