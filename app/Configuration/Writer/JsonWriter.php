<?php namespace Rancherize\Configuration\Writer;
use Rancherize\Configuration\Configuration;

/**
 * Class JsonWriter
 * @package Rancherize\Configuration\Writer
 */
class JsonWriter implements Writer {

	/**
	 * @param Configuration $configuration
	 * @param string $path
	 * @return mixed
	 */
	public function write(Configuration $configuration, string $path) {
		$config = $configuration->get();

		$jsonContent = json_encode($config, JSON_PRETTY_PRINT);

		file_put_contents($path, $jsonContent);
	}
}