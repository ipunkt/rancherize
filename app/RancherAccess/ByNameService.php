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
	public function findName(array $data, $name) {

		foreach($data['data'] as $stack) {
			if(strtolower($stack['name']) === strtolower($name) )
				return $stack;
		}

		throw new NameNotFoundException($name);
	}
}