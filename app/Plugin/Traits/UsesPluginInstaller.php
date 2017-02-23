<?php namespace Rancherize\Plugin\Traits;


use Rancherize\Plugin\Installer\PluginInstaller;

trait UsesPluginInstaller {

	/**
	 * @return PluginInstaller
	 */
	public function getPluginInstaller() {
		return container('plugin-installer');
	}
}