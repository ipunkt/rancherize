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

	/**
	 * @param $user
	 * @param $password
	 */
	public function setLogin($user, $password) {
		$this->setEnvironmentVariable('PMA_USER', $user);
		$this->setEnvironmentVariable('PMA_PASSWORD', $password);
	}

}