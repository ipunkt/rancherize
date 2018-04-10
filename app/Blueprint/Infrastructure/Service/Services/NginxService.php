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
		parent::__construct();
		$this->setImage('ipunktbs/nginx:1.10.2-7-1.3.0');
	}

}