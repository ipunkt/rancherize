<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;

use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class PmaService
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class PmaService extends Service {

	/**
	 * PmaService constructor.
	 */
	public function __construct() {
		$this->setName('PMA');
		$this->setTty(true);
		$this->setImage('phpmyadmin/phpmyadmin:4.6.2-3');
		$this->setKeepStdin(true);
	}

}