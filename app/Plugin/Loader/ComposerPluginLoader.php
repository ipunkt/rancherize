<?php namespace Rancherize\Plugin\Loader;

use Rancherize\Configuration\Configurable;

/**
 * Class ComposerPluginLoader
 */
class ComposerPluginLoader implements PluginLoader {
	/**
	 * @var Configurable
	 */
	private $configurable;

	/**
	 * ComposerPluginLoader constructor.
	 * @param Configurable $configurable
	 */
	public function __construct(Configurable $configurable) {
		$this->configurable = $configurable;
	}

	/**
	 * @param string $classpath
	 * @return
	 */
	public function register(string $classpath) {
		$plugins = $this->configurable->get('plugins');

		if( !is_array($plugins) )
			$plugins = [];

		if( in_array($classpath, $plugins) )
			throw new PluginAlreadyRegisteredException();
	}

	/**
	 * @param \Rancherize\Configuration\Configuration $configuration
	 * @return
	 */
	public function load(\Rancherize\Configuration\Configuration $configuration) {
		// TODO: Implement load() method.
	}
}