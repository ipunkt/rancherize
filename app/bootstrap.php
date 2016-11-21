<?php

require __DIR__.'/../vendor/autoload.php';

$container = new \Pimple\Container();

$container['configuration'] = function($container) {
	return new \Rancherize\Configuration\ArrayConfiguration();
};

$container['loader'] = function($container) {
	return new \Rancherize\Configuration\Loader\JsonLoader();
};

$container['writer'] = function($container) {
	return new \Rancherize\Configuration\Writer\JsonWriter();
};

$container['project-config-service'] = function($c) {
	return new \Rancherize\Configuration\Services\ProjectConfiguration(
		$c['loader'],
		$c['writer']
	);
};

$container['blueprint-factory'] = function($c) {
	return new \Rancherize\Blueprint\Factory\ConfigurationBlueprintFactory($c['configuration']);
};

if( ! function_exists('container') ) {

	/**
	 * Get an instance from the container
	 *
	 * @param string $instance
	 * @return \Pimple\Container
	 */
	function container(string $instance = null) {
		global $container;

		if($instance === null)
			return $container;

		return $container[$instance];
	}
}
