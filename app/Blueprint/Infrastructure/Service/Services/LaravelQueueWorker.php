<?php namespace Rancherize\Blueprint\Infrastructure\Service\Services;

use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class LaravelQueueWorker
 * @package Rancherize\Blueprint\Infrastructure\Service\Services
 */
class LaravelQueueWorker extends Service
{
    const DEFAULT_IMAGE_VERSION = 'php7.0-v1.0';

    private $__validImageVersions = [
        'php7.0-v1.0',
        'php7.1-v2.0',
        'php7.2-v3.0',
        'php7.3-v4.0',
        'php7.3-v4.1',
        'latest',
    ];

    public function __construct($imageVersion = null)
    {
        parent::__construct();
        $this->setName('LaravelQueueWorker');
        $this->setImageVersion($imageVersion);
        $this->setRestart(self::RESTART_UNLESS_STOPPED);
    }

    public function setImageVersion($version)
    {
        if ($version === null) {
            $this->setImage('ipunktbs/laravel-queue-worker:' . self::DEFAULT_IMAGE_VERSION);
            return;
        }

        if (!in_array($version, $this->__validImageVersions, false)) {
            return;
        }

        $this->setImage('ipunktbs/laravel-queue-worker:' . $version);
    }
}