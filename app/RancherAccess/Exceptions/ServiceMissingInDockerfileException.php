<?php namespace Rancherize\RancherAccess;

use Rancherize\Exceptions\Exception;

/**
 * Class ServiceMissingInDockerfileException
 * @package Rancherize\RancherAccess
 */
class ServiceMissingInDockerfileException extends Exception {
	/**
	 * @var int|string
	 */
	private $serviceName;

	/**
	 * ServiceMissingInDockerfileException constructor.
	 * @param int|string $serviceName
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct($serviceName, int $code = 0, \Exception $e = null) {
		$this->serviceName = $serviceName;
		parent::__construct("Service $serviceName was not found in the docker-compose.yml", $code, $e);
	}
}