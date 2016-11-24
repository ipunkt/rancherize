<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class NoActiveServiceException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * Thrown if RancherService::getActiveService could not find any candidates to be the currently active service
 */
class NoActiveServiceException extends Exception {

	public function __construct(string $serviceName, int $code = 31, \Exception $e = null) {
		parent::__construct("No Service with name $serviceName is was found");
	}

}