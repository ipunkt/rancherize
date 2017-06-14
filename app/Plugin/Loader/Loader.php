<?php namespace Rancherize\Plugin\Loader;

/**
 * Interface Loader
 * @package Rancherize\Plugin\Loader
 */
interface Loader {

	/**
	 * @param $identifier
	 * @return mixed
	 */
	public function load( $identifier );

}