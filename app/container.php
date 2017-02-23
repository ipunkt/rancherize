<?php

/**
 * populate the container
 */

$container = new \Pimple\Container();

/**
 * File handling
 */
$container['file-loader'] = function($c) {

	return new \Rancherize\File\FileLoader();
};

$container['file-writer'] = function($c) {
	return new \Rancherize\File\FileWriter();
};

/**
 * Configuration
 */
$container['configuration'] = function($container) {
	return new \Rancherize\Configuration\ArrayConfiguration();
};

$container['loader'] = function($c) {
	return new \Rancherize\Configuration\Loader\JsonLoader($c['file-loader']);
};

$container['writer'] = function($c) {
	return new \Rancherize\Configuration\Writer\JsonWriter($c['file-writer']);
};

$container['global-config-service'] = function($c) {
	return new \Rancherize\Configuration\Services\GlobalConfiguration(
		$c['loader'],
		$c['writer']
	);
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

$container['config-wrapper'] = function($c) {
	return new \Rancherize\Configuration\Services\ConfigWrapper(
		$c['global-config-service'],
		$c['project-config-service'],
		$c['configuration']
	);
};

$container['environment-service'] = function($c) {
	return new \Rancherize\Services\EnvironmentService();
};

$container['validate-service'] = function($c) {
		return new \Rancherize\Services\ValidateService();
};

$container['dockerfile-writer'] = function($c) {
	return new \Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter();
};

$container['service-writer'] = function($c) {
	return new \Rancherize\Blueprint\Infrastructure\Service\ServiceWriter($c['file-loader']);
};

$container['infrastructure-writer'] = function($c) {
	return new \Rancherize\Blueprint\Infrastructure\InfrastructureWriter($c['dockerfile-writer'], $c['service-writer']);
};

$container['build-service'] = function($c) {
	return new \Rancherize\Services\BuildService($c['validate-service'], $c['infrastructure-writer']);
};

$container['docker-service'] = function($c) {
	return new \Rancherize\Services\DockerService();
};

$container['api-service'] = function($c) {
	return new \Rancherize\RancherAccess\ApiService\CurlApiService();
};

$container['rancher-service'] = function($c) {
	return new \Rancherize\RancherAccess\RancherService( $c['api-service'] );
};

$container['blueprint-service'] = function($c) {
	return new \Rancherize\Services\BlueprintService($c['blueprint-factory']);
};

/**
 * Plugins
 */
$container['plugin-installer'] = function($c) {
	global $application;

	$nameParser = new \Rancherize\Plugin\Composer\ComposerPacketNameParser();
	$pathMaker = new \Rancherize\Plugin\Composer\ComposerPacketPathMaker();

	$installer = new \Rancherize\Plugin\Installer\ComposerPluginInstaller($nameParser, $pathMaker);

	$processHelper = $application->getHelperSet()->get('process');
	$installer->setProcessHelper($processHelper);

	return $installer;
};

$container['plugin-loader'] = function($c) {
	/*
	 * project-config is not set in this file - it is set in the rancherize.php once the project config was loaded for
	 * use with the plugin system
	 */
	return new \Rancherize\Plugin\Loader\ComposerPluginLoader($c['project-config'], $c['project-config-service']);
};

/**
 * Service Maker
 */
$container['php-fpm-maker'] = function($c) {
	$phpFpmMaker = new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker();

	$phpFpmMaker->addVersion(new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions\PHP70());
	$phpFpmMaker->addVersion(new \Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpVersions\PHP53());

	return $phpFpmMaker;
};

$container['custom-files-maker'] = function($c) {
	return new \Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles\CustomFilesMaker();
};

/**
 * Blueprint Validator
 */
$container['blueprint-rule-factory'] = function($c) {
	return new \Rancherize\Blueprint\Validation\RuleFactory\NamespaceRuleFactory('Rancherize\Blueprint\Validation\Rules');
};

$container['blueprint-validator'] = function($c) {
	return new \Rancherize\Blueprint\Validation\Validator($c['blueprint-rule-factory']);
};

/**
 * Prevent redeclaration in unit tests
 */
if( ! function_exists('container') ) {

	/**
	 * Helper function: container. Replaces accessing the container as global
	 */
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
