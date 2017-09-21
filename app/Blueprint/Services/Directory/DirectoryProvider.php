<?php namespace Rancherize\Blueprint\Services\Directory;

use Rancherize\Blueprint\Services\Directory\Service\SlashPrefixer;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DirectoryProvider
 * @package Rancherize\Blueprint\Services\Directory
 */
class DirectoryProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['slash-prefixer'] = function() {
			return new SlashPrefixer();
		};
	}

	/**
	 */
	public function boot() {
		// TODO: Implement boot() method.
	}
}