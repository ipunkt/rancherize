<?php


namespace Rancherize\Plugin;


use Pimple\Container;
use Symfony\Component\Console\Application;

/**
 * Class ProviderTrait
 * @package Rancherize\Plugin
 *
 * This trait implements setApplication and setContainer and saves them to $this->app and $this->container without forcing
 * an extension on you
 */
trait ProviderTrait {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @param Application $app
	 */
	public function setApplication(Application $app) {
		$this->app = $app;
	}

	/**
	 * @param Container $container
	 */
	public function setContainer(Container $container) {
		$this->container = $container;
	}
}