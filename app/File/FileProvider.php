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
		$this->container[FileLoader::class] = function() {

			return new FileLoader();
		};

		$this->container[FileWriter::class] = function() {
			return new FileWriter();
		};
	}

	/**
	 */
	public function boot() {
	}
}