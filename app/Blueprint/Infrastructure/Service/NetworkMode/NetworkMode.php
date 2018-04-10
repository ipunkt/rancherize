<?php namespace Rancherize\Blueprint\Infrastructure\Service\NetworkMode;

/**
 * Interface NetworkMode
 * @package Rancherize\Blueprint\Infrastructure\Service\NetworkMode
 */
interface NetworkMode {

	/**
	 * @return string
	 */
	function getNetworkMode(): string;

}