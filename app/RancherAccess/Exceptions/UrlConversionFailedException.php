<?php namespace Rancherize\RancherAccess;
use Rancherize\Exceptions\Exception;

/**
 * Class UrlConversionFailedException
 * @package Rancherize\RancherAccess
 */
class UrlConversionFailedException extends Exception {

	/**
	 * UrlConversionFailedException constructor.
	 * @param string $url
	 * @param string $version
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($url, $version, $code = 0, \Exception $e = null) {
		parent::__construct("Failed to convert '$url' to the format required for $version", $code, $e);
	}
}