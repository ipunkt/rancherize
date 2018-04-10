<?php namespace Rancherize\Blueprint\Infrastructure;

use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Network\NetworkWriter;
use Rancherize\Blueprint\Infrastructure\Service\ServiceWriter;
use Rancherize\Blueprint\Infrastructure\Volume\VolumeWriter;
use Rancherize\File\FileLoader;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class InfrastructureProvider
 * @package Rancherize\Blueprint\Infrastructure
 */
class InfrastructureProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container[DockerfileWriter::class] = function() {
			return new DockerfileWriter();
		};
		$this->container['dockerfile-writer'] = function($c) {
			return $c[DockerfileWriter::class];
		};

		$this->container[ServiceWriter::class] = function($c) {
			return new ServiceWriter($c[FileLoader::class], $c['event'], $c[DockerfileWriter::class]);
		};
		$this->container['service-writer'] = function($c) {
			return $c[ServiceWriter::class];
		};

		$this->container[VolumeWriter::class] = function($c) {
			return new VolumeWriter($c[FileLoader::class]);
		};
		$this->container['volume-writer'] = function($c) {
			return $c[VolumeWriter::class];
		};

		$this->container[NetworkWriter::class] = function($c) {
			return new NetworkWriter( $c[FileLoader::class] );
		};

		$this->container[\Rancherize\Blueprint\Infrastructure\InfrastructureWriter::class] = function($c) {
			return new \Rancherize\Blueprint\Infrastructure\InfrastructureWriter(
				$c[DockerfileWriter::class],
				$c[ServiceWriter::class],
				$c[VolumeWriter::class],
				$c[NetworkWriter::class]);
		};

		$this->container['infrastructure-writer'] = function($c) {
			return $c[\Rancherize\Blueprint\Infrastructure\InfrastructureWriter::class];
		};

		$this->container['shared-network-mode'] = 'container:';
	}

	/**
	 */
	public function boot() {
	}
}