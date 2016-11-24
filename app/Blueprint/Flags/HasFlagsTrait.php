<?php


namespace Rancherize\Blueprint\Flags;


/**
 * Class HasFlagsTrait
 * @package Rancherize\Blueprint\Flags
 *
 * Array based implementation of the 'setFlag' function of the Blueprint.
 * Can be used as drop-in implementation, asking getFlag if the flag was set
 */
trait HasFlagsTrait {

	protected $flags = [];

	/**
	 * Set the given flag to the given value
	 *
	 * @param string $flag
	 * @param $value
	 */
	public function setFlag(string $flag, $value) {
		$this->flags[$flag] = $value;
	}

	/**
	 * Return the set value for the given flag. If the flag was not set then the given default value is returned
	 *
	 * @param string $flag
	 * @param mixed $default
	 * @return null
	 */
	public function getFlag(string $flag, $default = null) {
		if( !array_key_exists($flag, $this->flags) )
			return $default;
		return $this->flags[$flag];
	}
}