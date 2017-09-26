<?php


namespace Rancherize\Commands\Traits;


use Rancherize\RancherAccess\RancherService;

/**
 * Class RancherTrait
 * @package Rancherize\Commands\Traits
 *
 * Typehinted access to the RancherService in the container
 */
trait RancherTrait {

	/**
	 * @return RancherService
	 */
	public function getRancher() : RancherService {
		return container(RancherService::class);
	}
}