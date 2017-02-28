<?php namespace Rancherize\General\Services;

use Rancherize\General\Exceptions\KeyNotFoundException;

/**
 * Class ByKeyService
 * @package Rancherize\General\Services
 *
 * Case insensitive retrieval of a value by key
 */
class ByKeyService {
	/**
	 * @param string $key
	 * @param array $data
	 * @return array ['CaSeSeNsItIvE KeY', $value]
	 */
	public function byKey(string $key, array $data) {

		foreach($data as $currentKey => $currentValue) {
			if( strtolower($key) === strtolower($currentKey) )
				return [$currentKey, $currentValue];
		}

		throw new KeyNotFoundException($key, $data);
	}
}