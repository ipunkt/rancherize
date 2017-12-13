<?php namespace Rancherize\Blueprint\Infrastructure\Network;

use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\File\FileLoader;
use Rancherize\File\FileWriter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class NetworkWriter
 * @package Rancherize\Blueprint\Infrastructure\Network
 */
class NetworkWriter {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var FileLoader
	 */
	private $fileLoader;

	/**
	 * NetworkWriter constructor.
	 * @param FileLoader $fileLoader
	 */
	public function __construct(FileLoader $fileLoader) {
		$this->fileLoader = $fileLoader;
	}

	/**
	 * @param Network $
	 * @param FileWriter $fileWriter
	 */
	public function write( Network $network, FileWriter $fileWriter ) {

		$content = [];

		if( $network->isExternal() ) {
			$content['external'] = [
				'name' => $network->getExternalName()
			];
		}

		$this->writeYaml($this->path . '/docker-compose.yml', $network, $fileWriter, $content);
	}

	/**
	 * @param string $path
	 */
	public function setPath( string $path ) {
		$this->path = $path;
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
	 * @param Network $network
	 * @param FileWriter $fileWriter
	 * @param $content
	 */
	protected function writeYaml($targetFile, Network $network, FileWriter $fileWriter, $content) {

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

			if(!array_key_exists('networks', $dockerData))
				$dockerData['networks'] = [];

			$dockerData['networks'][$network->getName()] = $content;

		} catch (FileNotFoundException $e) {
			$dockerData = [
				'version' => '2',
				'services' => [],
				'networks' => [
					$network->getName() => $content
				]
			];
		}

		$dockerYaml = Yaml::dump($dockerData, 100, 2);

		$fileWriter->put($targetFile, $dockerYaml);
	}
}