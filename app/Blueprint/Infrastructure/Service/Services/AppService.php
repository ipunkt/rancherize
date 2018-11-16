<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;

use Rancherize\Blueprint\Infrastructure\Service\NetworkMode\NoNetworkMode;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class AppService
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class AppService extends Service {

    /**
     * AppService constructor.
     * @param $image
     */
    public function __construct($image) {
        parent::__construct();
        $this->setImage($image);
        $this->setRestart(self::RESTART_START_ONCE);
        $this->setCommand('/bin/true');
        $this->setKeepStdin(true);
        $this->setNetworkMode(new NoNetworkMode());
    }

}