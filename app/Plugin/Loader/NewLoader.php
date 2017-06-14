<?php namespace Rancherize\Plugin\Loader;

/**
 * Class NewLoader
 * @package Rancherize\Plugin\Loader
 */
class NewLoader implements Loader {

	/**
	 * @param $identifier
	 * @return mixed
	 */
	public function load( $identifier ) {
		if( !class_exists($identifier) )
			throw new IdentifierNotFoundException($identifier, "Class '$identifier' not found");

		return new $identifier;
	}
}