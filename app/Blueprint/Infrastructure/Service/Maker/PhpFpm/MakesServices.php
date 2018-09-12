<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Interface MakesServices
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface MakesServices {

	/**
	 * @param $serviceName
	 * @param $command
	 * @param Service $mainService
	 * @param bool $isSidekick
	 * @return Service
	 */
	function makeCommand($serviceName, $command, Service $mainService, $isSidekick = true);

}