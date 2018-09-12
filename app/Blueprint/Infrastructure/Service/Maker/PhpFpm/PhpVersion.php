<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Closure;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Interface PhpVersion
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface PhpVersion {

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param Infrastructure $infrastructure
	 * @param Closure|null $customize
	 * @return
	 */
	function make(Configuration $config, Service $mainService, Infrastructure $infrastructure, Closure $customize = null);

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