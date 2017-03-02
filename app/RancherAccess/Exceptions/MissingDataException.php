<?php namespace Rancherize\RancherAccess\Exceptions;

use Rancherize\Exceptions\Exception;

/**
 * Class MissingDataException
 * @package Rancherize\RancherAccess\Exceptions
 */
class MissingDataException extends Exception {
	/**
	 * @var string
	 */
	private $field;
	/**
	 * @var array
	 */
	private $availableFields;

	/**
	 * MissingDataException constructor.
	 * @param string $field
	 * @param array $availableFields
	 */
	public function __construct( string $field, array $availableFields, int $code = 0, \Exception $e = null) {
		$this->field = $field;
		$this->availableFields = $availableFields;
		$availableFieldNames = implode(',', $availableFields);

		parent::__construct("Failed to find field $field. Available fields: $availableFieldNames", $code, $e);
	}

}