<?php namespace Rancherize\General\Services;

/**
 * Class NameIsPathChecker
 * @package Rancherize\General\Services
 */
class NameIsPathChecker {

	/**
	 * @param $name
	 * @return bool
	 */
	public function isPath($name) {
		$dashFound = (strpos($name, '/') !== FALSE);

		if($dashFound)
			return true;

		return false;
	}
}