<?php namespace Rancherize\Docker;

use Rancherize\Docker\NameCleaner\NameCleaner;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;

/**
 * Class DockerProvider
 * @package Rancherize\Docker
 */
class DockerProvider implements Provider {

    use ProviderTrait;

    /**
     */
    public function register() {
        $this->container[DockerAccessService::class] = function($c) {
            return new DockerAccessConfigService($c['event']);
        };

        $this->container[NameCleaner::class] = function () {
            return new NameCleaner;
        };
    }

    /**
     */
    public function boot() {
    }
}