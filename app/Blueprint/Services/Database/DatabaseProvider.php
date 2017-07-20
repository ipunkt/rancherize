<?php namespace Rancherize\Blueprint\Services\Database;

use Rancherize\Blueprint\Services\Database\DatabaseBuilder\DatabaseBuilder;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DatabaseProvider
 * @package Rancherize\Blueprint\Services\Database
 */
class DatabaseProvider implements \Rancherize\Plugin\Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['database-builder'] = function() {
			return new DatabaseBuilder();
		};
	}

	/**
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}