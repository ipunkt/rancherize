<?php namespace Rancherize\Configuration\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class FileNotFoundException
 * @package Rancherize\Configuration\Exceptions
 *
 * Thrown if a  file was requested that is not present in the filesystem
 */
class FileNotFoundException extends Exception  {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * FileNotFoundException constructor.
	 * @param string $path
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($path, $code = 0, \Exception $e = null) {
		parent::__construct("File $path not found.", $code, $e);

		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}
}