<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class MultipleActiveServicesException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * This exception is thrown if RancherService::getActiveService finds more than one candidate for the currently running
 * service
 */
class MultipleActiveServicesException extends Exception  {

	/**
	 * MultipleActiveServicesException constructor.
	 * @param string $name
	 * @param string[] $matchingServices
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $name, array $matchingServices, int $code = 40, \Exception $e = null) {
		$matchingServiceNames = implode(', ', $matchingServices);
		parent::__construct("More than one stack was found matching $name: $matchingServiceNames", $code, $e);
	}
}