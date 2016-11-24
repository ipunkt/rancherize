<?php namespace Rancherize\Configuration\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class SaveFailedException
 * @package Rancherize\Configuration\Exceptions
 *
 * Thrown by the FileWriter if the write operation to the disk was not successful
 */
class SaveFailedException extends Exception  {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * SaveFailedException constructor.
	 * @param string $path
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($path, $code = 0, \Exception $e = null) {
		$this->path = $path;
		parent::__construct("Failed to save to $path", $code, $e);

	}
}