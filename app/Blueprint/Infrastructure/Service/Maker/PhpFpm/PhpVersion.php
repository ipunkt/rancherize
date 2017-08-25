<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Interface PhpVersion
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface PhpVersion {



	/**
	 * @return $this
	 */
	function setMemoryLimit($limit);

	/**
	 * @param string $hostDirectory
	 * @param string $containerContainerDirectory
	 * @return $this
	 */
	function setAppMount(string $hostDirectory, string $containerContainerDirectory);

	/**
	 * @param Service $appService
	 * @return $this
	 */
	function setAppService(Service $appService);

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 */
	function make(Configuration $config, Service $mainService, Infrastructure $infrastructure);

	/**
	 * @param $commandName
	 * @param $command
	 * @param Service $mainService
	 * @return Service
	 */
	function makeCommand($commandName, $command, Service $mainService);

	/**
	 * @return string
	 */
	function getVersion();
}