<?php namespace Rancherize\Blueprint\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class BlueprintNotFoundException
 * @package Rancherize\Blueprint\Exceptions
 *
 * Indicates that the requested blueprint is not known
 */
class BlueprintNotFoundException extends Exception  {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * BlueprintNotFoundException constructor.
	 * @param string $name
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct($name, $code = 0, \Exception $e = null) {

		parent::__construct("Blueprint not found: $name", $code, $e);
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
}