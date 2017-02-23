<?php namespace Rancherize\Plugin\Loader;

use Rancherize\Exceptions\Exception;

/**
 * Class PluginAlreadyRegisteredException
 * @package Rancherize\Plugin\Loader
 */
class PluginAlreadyRegisteredException extends Exception {
	/**
	 * @var string
	 */
	private $classpath;

	/**
	 * PluginAlreadyRegisteredException constructor.
	 * @param string $classpath
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($classpath, int $code = 0, \Exception $e = null) {
		$this->classpath = $classpath;
		parent::__construct("Plugin $classpath is already registered.", $code, $e);
	}
}