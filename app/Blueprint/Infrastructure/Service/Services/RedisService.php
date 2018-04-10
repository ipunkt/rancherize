<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class RedisService
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class RedisService extends Service  {

	public function __construct() {
		parent::__construct();
		$this->setName('Redis');
		$this->setRestart( self::RESTART_UNLESS_STOPPED );
		$this->setTty(true);
		$this->setKeepStdin(true);
		$this->setImage("redis:3.2-alpine");
	}
}