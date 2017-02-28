<?php namespace Rancherize\General\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class KeyNotFoundException
 * @package Rancherize\General\Services
 */
class KeyNotFoundException extends Exception {
	/**
	 * @var string
	 */
	private $key;
	/**
	 * @var array
	 */
	private $availableKeys;

	/**
	 * KeyNotFoundException constructor.
	 * @param string $key
	 * @param array $availableKeys
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $key, array $availableKeys, int $code = 0, \Exception $e = null) {
		$this->key = $key;
		$availableKeyNames = implode(',', $availableKeys);

		parent::__construct("Requested key '$key'' was not found within the available keys: $availableKeyNames", $code, $e);
		$this->availableKeys = $availableKeys;
	}
}