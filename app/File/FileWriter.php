<?php namespace Rancherize\File;
use Rancherize\Configuration\Exceptions\SaveFailedException;

/**
 * Class FileWriter
 * @package Rancherize\File
 *
 * This is a wrapper around file_put_contents to enable mocking it in unit tests
 */
class FileWriter {

	/**
	 * Write content to file
	 *
	 * @param string $path
	 * @param string $content
	 */
	public function put(string $path, string $content) {
		if( !file_put_contents($path, $content) )
			throw new SaveFailedException($path, 100);
	}
}