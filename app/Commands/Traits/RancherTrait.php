<?php


namespace Rancherize\Commands\Traits;


use Rancherize\RancherAccess\RancherService;

trait RancherTrait {

	/**
	 * @return RancherService
	 */
	public function getRancher() : RancherService {
		return container('rancher-service');
	}
}