<?php namespace Rancherize\Docker\Exceptions;

/**
 * Class PushFailedException
 * @package Rancherize\Docker\Exceptions
 *
 * This exception is thrown if a docker push operation was not succesful
 */
class PushFailedException extends DockerException  {
	/**
	 * @var string
	 */
	private $imageName;

	/**
	 * PushFailedException constructor.
	 * @param string $imageName
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($imageName, int $code = 0, \Exception $e = null) {

		$this->imageName = $imageName;

		parent::__construct("Docker push failed: $imageName", $code, $e);
	}
}