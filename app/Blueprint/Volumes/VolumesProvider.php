<?php namespace Rancherize\Blueprint\Volumes;

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
		$this->container['volume-service'] = function() {
			return new VolumeService;
		};
	}

	/**
	 */
	public function boot() {
	}
}