<?php namespace Rancherize\RancherAccess;

use Rancherize\RancherAccess\Exceptions\NameNotFoundException;

/**
 * Class ByNameService
 * @package Rancherize\RancherAccess
 */
class ByNameService {

	/**
	 * @param array $data
	 * @param $name
	 * @return array
	 */
	public function findName(array $data, $name, $field = null) {
		if($field == null)
			$field = 'name';

		foreach($data as $stack) {
			if(strtolower($stack[$field]) === strtolower($name) )
				return $stack;
		}

		throw new NameNotFoundException($name);
	}
}