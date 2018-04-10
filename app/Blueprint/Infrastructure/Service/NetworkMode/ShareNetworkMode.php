<?php namespace Rancherize\Blueprint\Infrastructure\Service\NetworkMode;

use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class ShareNetworkMode
 * @package Rancherize\Blueprint\Infrastructure\Service\NetworkMode
 */
class ShareNetworkMode implements NetworkMode {
	/**
	 * @var Service
	 */
	private $service;

	/**
	 * ShareNetworkMode constructor.
	 * @param Service $service
	 */
	public function __construct( Service $service ) {
		$this->service = $service;
	}

	/**
	 * @return string
	 */
	public function getNetworkMode(): string {
		return container( 'shared-network-mode' ) . $this->service->getName();
	}
}