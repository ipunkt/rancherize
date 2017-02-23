<?php namespace Rancherize\Plugin\Traits;

use Rancherize\Plugin\Loader\PluginLoader;

trait UsesPluginLoader {
	/**
	 * @return PluginLoader
	 */
	public function getPluginLoader() {
		return container('plugin-loader');
	}
}