<?php namespace Rancherize\File;

use Rancherize\Configuration\Exceptions\FileNotFoundException;

/**
 * Class FileLoader
 *
 * This is a wrapper around file_get_contents to enable mocking it in unit tests
 */
class FileLoader {

	/**
	 * Load file from disk
	 *
	 * @param $path
	 * @return string
	 */
	public function get(string $path) : string {

		if(! file_exists($path) )
			throw new FileNotFoundException($path, 200);

		return file_get_contents($path);
	}
}