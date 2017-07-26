<?php namespace Rancherize\Blueprint\Services\Database\HasDatabase;

use Rancherize\Configuration\Configuration;

/**
 * Class HasDatabase
 * @package Rancherize\Blueprint\Services\Database\HasDatabase
 */
class HasDatabase {

	/**
	 * @param Configuration $configuration
	 */
	public function hasDatabase( Configuration $configuration ) {
		if ($configuration->get('add-database', false))
			return true;

		return false;
	}

}