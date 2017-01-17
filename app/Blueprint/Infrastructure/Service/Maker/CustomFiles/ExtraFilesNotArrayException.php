<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles;
use Rancherize\Exceptions\Exception;

/**
 * Class ExtraFilesNotArrayException
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles
 */
class ExtraFilesNotArrayException extends Exception {

	/**
	 * ExtraFilesNotArrayException constructor.
	 */
	public function __construct($code = 0, \Exception $e = null) {
		parent::__construct("Values for `extra-files` was not given as array", $code, $e);
	}
}