<?php namespace Rancherize\Blueprint\Volumes;

use Rancherize\Blueprint\Volumes\VolumeService\VolumeParser\ContainerParserFactory;
use Rancherize\Blueprint\Volumes\VolumeService\VolumeParser\ObjectParser;
use Rancherize\Blueprint\Volumes\VolumeService\VolumeParser\StringParser;
use Rancherize\Blueprint\Volumes\VolumeService\VolumeService;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class VolumesProvider
 * @package Rancherize\Blueprint\Volumes
 */
class VolumesProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['volume-parser.string'] = function() {
			return new StringParser();
		};

		$this->container['volume-parser.object'] = function() {
			return new ObjectParser();
		};

		$this->container['volume-parser-factory'] = function($c) {
			return new ContainerParserFactory($c);
		};

		$this->container['volume-service'] = function($c) {
			return new VolumeService( $c['volume-parser-factory'] );
		};
	}

	/**
	 */
	public function boot() {
	}
}