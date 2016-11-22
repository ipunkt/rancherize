<?php


namespace Rancherize\Blueprint\Flags;


trait HasFlagsTrait {

	protected $flags = [];

	/**
	 * @param string $flag
	 * @param $value
	 */
	public function setFlag(string $flag, $value) {
		$this->flags[$flag] = $value;
	}

	/**
	 * @param string $flag
	 * @param null $default
	 */
	public function getFlag(string $flag, $default = null) {
		if( !array_key_exists($flag, $this->flags) )
			return $default;
		return $this->flags[$flag];
	}
}