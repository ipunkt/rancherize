<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class LaravelQueueWorker
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class LaravelQueueWorker extends Service  {

	public function __construct() {
		$this->setName('LaravelQueueWorker');
        $this->setImage('ipunktbs/laravel-queue-worker:php7.0-v1.0');
        $this->setRestart( self::RESTART_UNLESS_STOPPED );
//		$this->setTty(true);
//		$this->setKeepStdin(true);
	}
}