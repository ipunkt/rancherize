<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class NginxService
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class NginxService extends Service {

	/**
	 * NginxService constructor.
	 */
	public function __construct() {
		$this->setImage('ipunktbs/nginx:1.9.7-7-1.2.0');
	}

}