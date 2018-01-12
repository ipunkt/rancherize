<?php

/**
 * populate the container
 */

use Symfony\Component\EventDispatcher\EventDispatcher;

$container = new \Pimple\Container();

$container[EventDispatcher::class] = function () {
	return new EventDispatcher();
};

$container['event'] = function ($c) {
	return $c[EventDispatcher::class];
};


/**
 * Configuration
 */
$container['configuration'] = function() {
	return new \Rancherize\Configuration\ArrayConfiguration();
};

$container['loader'] = function($c) {
	return new \Rancherize\Configuration\Loader\JsonLoader($c[\Rancherize\File\FileLoader::class]);
};

$container['writer'] = function($c) {
	return new \Rancherize\Configuration\Writer\JsonWriter($c[\Rancherize\File\FileWriter::class]);
};

$container[\Rancherize\Configuration\Services\GlobalConfiguration::class] = function($c) {
	return new \Rancherize\Configuration\Services\GlobalConfiguration(
		$c['loader'],
		$c['writer']
	);
};

$container[\Rancherize\Configuration\Services\ProjectConfiguration::class] = function($c) {
	return new \Rancherize\Configuration\Services\ProjectConfiguration(
		$c['loader'],
		$c['writer']
	);
};

$container['config-wrapper'] = function($c) {
	return new \Rancherize\Configuration\Services\ConfigWrapper(
		$c[\Rancherize\Configuration\Services\GlobalConfiguration::class],
		$c[\Rancherize\Configuration\Services\ProjectConfiguration::class],
		$c['configuration']
	);
};

$container['environment-service'] = function() {
	return new \Rancherize\Services\EnvironmentService();
};

$container['validate-service'] = function() {
		return new \Rancherize\Services\ValidateService();
};

$container['build-service'] = function($c) {
	return new \Rancherize\Services\BuildService($c['validate-service'], $c['infrastructure-writer'], $c['event']);
};

$container[\Rancherize\Services\BuildService::class] = function($c) {
	return new \Rancherize\Services\BuildService($c['validate-service'], $c['infrastructure-writer'], $c['event']);
};

$container[\Rancherize\Services\DockerService::class] = function() {
	return new \Rancherize\Services\DockerService();
};

$container[\Rancherize\Services\DockerService::class] = function() {
	return new \Rancherize\Services\DockerService();
};

$container['composer-packet-name-parser'] = function() {
	return new \Rancherize\Plugin\Composer\ComposerPacketNameParser();
};

$container['composer-packet-path-maker'] = function() {
	return new \Rancherize\Plugin\Composer\ComposerPacketPathMaker();
};

/**
 * Plugins
 */
$container[\Rancherize\Plugin\Installer\PluginInstaller::class] = function($c) {
	/**
	 * @var \Symfony\Component\Console\Application $application
	 */
	$application = $c['app'];

	$nameParser = $c['composer-packet-name-parser'];
	$pathMaker = $c['composer-packet-path-maker'];

	$installer = new \Rancherize\Plugin\Installer\ComposerPluginInstaller($nameParser, $pathMaker);

	/**
	 * @var \Symfony\Component\Console\Helper\ProcessHelper $processHelper
	 */
	$processHelper = $application->getHelperSet()->get('process');
	$installer->setProcessHelper($processHelper);

	return $installer;
};

$container['loader-interface'] = function () {
	return new \Rancherize\Plugin\Loader\NewLoader();
};

$container[\Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator::class] = function($c) {
	return new \Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator($c['loader-interface']);
};

$container[\Rancherize\Composer\PackageNameParser::class] = function() {
	return new \Rancherize\Composer\PackageNameParser();
};

$container['custom-files-maker'] = function() {

	return new \Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles\CustomFilesMaker();
};

/**
 * Blueprint Validator
 */
$container['blueprint-rule-factory'] = function() {
	return new \Rancherize\Blueprint\Validation\RuleFactory\NamespaceRuleFactory('Rancherize\Blueprint\Validation\Rules');
};

$container['blueprint-validator'] = function($c) {
	return new \Rancherize\Blueprint\Validation\Validator($c['blueprint-rule-factory']);
};

$container[\Rancherize\Docker\DockerComposeReader\DockerComposeReader::class] = function() {
	return new Rancherize\Docker\DockerComposeReader\DockerComposeReader();
};

$container['docker-compose-reader'] = function($c) {
	return $c[\Rancherize\Docker\DockerComposeReader\DockerComposeReader::class];
};

$container[\Rancherize\Docker\RancherComposeReader\RancherComposeReader::class] = function() {
	return new Rancherize\Docker\RancherComposeReader\RancherComposeReader();
};

$container['rancher-compose-reader'] = function($c) {
	return $c[\Rancherize\Docker\RancherComposeReader\RancherComposeReader::class];
};

$container[\Rancherize\Docker\DockerComposerVersionizer::class] = function() {
	return new \Rancherize\Docker\DockerComposerVersionizer();
};

$container['docker-compose-versionizer'] = function($c) {
	return $c[\Rancherize\Docker\DockerComposerVersionizer::class];
};

$container[\Rancherize\General\Services\ByKeyService::class] = function() {
	return new \Rancherize\General\Services\ByKeyService();
};

$container['by-key-service'] = function($c) {
	return $c[\Rancherize\General\Services\ByKeyService::class];
};

$container[\Rancherize\General\Services\NameIsPathChecker::class] = function() {
	return new \Rancherize\General\Services\NameIsPathChecker();
};

$container['name-is-path-checker'] = function($c) {
	return $c[\Rancherize\General\Services\NameIsPathChecker::class];
};
$pluginProvider = new \Rancherize\Plugin\PluginProvider();
$pluginProvider->setContainer($container);
$pluginProvider->register();
$pluginProvider->boot();

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
