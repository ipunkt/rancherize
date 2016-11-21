<?php namespace Rancherize\Configuration\Loader;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Exceptions\FileNotFoundException;

/**
 * Class JsonLoader
 * @package Rancherize\Configuration\Loader
 */
class JsonLoader implements Loader {

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * @param Configurable $configurable
	 * @param string $path
	 */
	public function load(Configurable $configurable, string $path) {

		if( !file_exists($path) )
			throw new FileNotFoundException($path);

		$fileContents = file_get_contents($path);

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