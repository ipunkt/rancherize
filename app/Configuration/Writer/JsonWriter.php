<?php namespace Rancherize\Configuration\Writer;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\SaveFailedException;
use Rancherize\File\FileWriter;

/**
 * Class JsonWriter
 * @package Rancherize\Configuration\Writer
 */
class JsonWriter implements Writer {
	/**
	 * @var FileWriter
	 */
	private $fileWriter;

	/**
	 * JsonWriter constructor.
	 * @param FileWriter $fileWriter
	 */
	public function __construct(FileWriter $fileWriter) {
		$this->fileWriter = $fileWriter;
	}

	/**
	 * @param Configuration $configuration
	 * @param string $path
	 * @return mixed
	 */
	public function write(Configuration $configuration, string $path) {
		$config = $configuration->get();

		$jsonContent = json_encode($config, JSON_PRETTY_PRINT);

		$this->fileWriter->put($path, $jsonContent);

	}
}