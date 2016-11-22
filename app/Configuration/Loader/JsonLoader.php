<?php namespace Rancherize\Configuration\Loader;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\File\FileLoader;

/**
 * Class JsonLoader
 * @package Rancherize\Configuration\Loader
 *
 * Load a json file into a configuration
 */
class JsonLoader implements Loader {

	/**
	 * @var string
	 */
	protected $prefix = '';
	/**
	 * @var FileLoader
	 */
	private $fileLoader;

	/**
	 * JsonLoader constructor.
	 *
	 * @param FileLoader $fileLoader
	 */
	public function __construct(FileLoader $fileLoader) {
		$this->fileLoader = $fileLoader;
	}

	/**
	 * @param Configurable $configurable
	 * @param string $path
	 */
	public function load(Configurable $configurable, string $path) {

		$fileContents = $this->fileLoader->get($path);

		$values = json_decode($fileContents, true);
		foreach($values as $key => $value) {
			$configurable->set($key, $value);
		}

	}

	/**
	 * @param string $prefix
	 * @return JsonLoader
	 */
	public function setPrefix(string $prefix = null): Loader {
		$this->prefix = $prefix;
		return $this;
	}
}