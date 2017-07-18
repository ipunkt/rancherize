<?php namespace Rancherize\Plugin;
use Pimple\Container;
use Symfony\Component\Console\Application;

/**
 * Interface DatabaseProvider
 * @package Rancherize\Plugin
 */
interface Provider {

	/**
	 * @param Application $app
	 */
	function setApplication(Application $app);

	/**
	 * @param Container $container
	 */
	function setContainer(Container $container);

	/**
	 */
	function register();

	/**
	 */
	function boot();

}