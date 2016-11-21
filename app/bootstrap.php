<?php

require __DIR__.'/../vendor/autoload.php';

$container = new \Pimple\Container();

$container['configuration'] = function($container) {
	return new \Rancherize\Configuration\ArrayConfiguration();
};

$container['loader'] = function($container) {
	return new \Rancherize\Configuration\Loader\JsonLoader();
};

if( ! function_exists('container') ) {

	/**
	 * Get an instance from the container
	 *
	 * @param string $instance
	 * @return \Pimple\Container
	 */
	function container(string $instance) {
		global $container;

		return $container[$instance];
	}
}
