<?php namespace Rancherize\Blueprint\Infrastructure\Service;

/**
 * Class ExtraInformationNotFoundException
 * @package Rancherize\Blueprint\Infrastructure\Service
 */
class ExtraInformationNotFoundException extends \RuntimeException {
	/**
	 * @var string
	 */
	private $identifier;

	/**
	 * ExtraInformationNotFoundException constructor.
	 * @param string $identifier
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($identifier, $code = 0, \Exception $e = null) {
		$this->identifier = $identifier;
		parent::__construct("ExtraInformation identifier $identifier not found", $code, $e);
	}
}