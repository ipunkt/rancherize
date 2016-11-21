<?php namespace Rancherize\Configuration;

/**
 * Class ArrayConfiguration
 * @package Rancherize\Configuration
 */
class ArrayConfiguration implements Configuration, Configurable  {

	/**
	 * @var mixed[]
	 */
	protected $values;

	/**
	 * ArrayConfiguration constructor.
	 * @param array $values
	 */
	public function __construct($values = null) {
		if( $values === null )
			$values = [];

		$this->values = $values;
	}

	/**
	 * @param string $key
	 */
	public function set(string $key, $value) {

		$keyParts = explode('.', $key);

		$currentValues = &$this->values;

		foreach($keyParts as $keyPart) {

			if( !is_array($currentValues) )
				$currentValues = [];

			if( !array_key_exists($keyPart, $currentValues) )
				$currentValues[$keyPart] = [];

			$currentValues = &$currentValues[$keyPart];
		}

		$currentValues = $value;

	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key) : bool {
		$keyParts = explode('.', $key);

		$currentValues = $this->values;

		foreach($keyParts as $keyPart) {
			if( !array_key_exists($keyPart, $currentValues) )
				return false;

			$currentValues = $currentValues[$keyPart];
		}

		return true;
	}

	/**
	 * @param string $key
	 * @param null $default
	 * @return mixed
	 */
	public function get(string $key = null, $default = null) {

		if($key === null)
			$keyParts = [];
		else
			$keyParts = explode('.', $key);

		$currentValues = $this->values;

		foreach($keyParts as $keyPart) {
			if( !array_key_exists($keyPart, $currentValues) )
				return $default;

			$currentValues = $currentValues[$keyPart];
		}

		return $currentValues;
	}
}