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
		if( !file_put_contents($path, $content) && strlen($content) !== 0 )
			throw new SaveFailedException($path, 100);

        /**
         * Set user/group corresponding to executing user
         */
		if ( !empty($_ENV['USER_ID']) )
		    chown($path, (int) getenv('USER_ID'));

        if ( !empty($_ENV['GROUP_ID']) )
		    chgrp($path, (int) getenv('GROUP_ID'));
	}

	/**
	 * @param string $path
	 * @param string $content
	 */
	public function append(string $path, string $content) {
		if( !file_put_contents($path, $content, FILE_APPEND) )
			throw new SaveFailedException($path, 100);
	}
}