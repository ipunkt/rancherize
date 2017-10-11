<?php namespace Rancherize\File;

use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class FileProvider
 * @package Rancherize\File
 */
class FileProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {

		/**
		 * File handling
		 */
		$container[FileLoader::class] = function() {

			return new FileLoader();
		};

		$container['file-writer'] = function() {
			return new FileWriter();
		};
	}

	/**
	 */
	public function boot() {
	}
}