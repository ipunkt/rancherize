<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class CustomFilesMaker
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles
 */
class CustomFilesMaker {

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	public function make(Configuration $config, Service $mainService, Infrastructure $infrastructure) {

		if ( !$config->get('mount-workdir', false) )
			return;

		if ( !$config->has('extra-files') )
			return;

		$extraFiles = $config->get('extra-files');

		if( !is_array($extraFiles) )
			throw new ExtraFilesNotArrayException();

		foreach($extraFiles as $filePath) {
			$fileName = basename($filePath);

			$mainService->addVolume(getcwd().DIRECTORY_SEPARATOR.$filePath, '/opt/custom/'.$fileName);
		}
	}

	/**
	 * @param Dockerfile $dockerfile
	 */
	public function applyToDockerfile(Configuration $config, Dockerfile $dockerfile) {
		if ( !$config->has('extra-files') )
			return;

		$extraFiles = $config->get('extra-files');

		if( !is_array($extraFiles) )
			throw new ExtraFilesNotArrayException();

		$dockerfile->addVolume('/opt/custom');
		foreach($extraFiles as $filePath) {
			$fileName = basename($filePath);

			$dockerfile->copy($filePath, '/opt/custom/'.$fileName);
		}
	}
}
