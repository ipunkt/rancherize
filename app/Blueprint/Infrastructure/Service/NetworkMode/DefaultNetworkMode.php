<?php namespace Rancherize\Blueprint\Infrastructure\Service\NetworkMode;

/**
 * Class DefaultNetworkMode
 * @package Rancherize\Blueprint\Infrastructure\Service\NetworkMode
 */
class DefaultNetworkMode implements NetworkMode {

	/**
	 * @return string
	 */
	public function getNetworkMode(): string {
		return '';
	}
}