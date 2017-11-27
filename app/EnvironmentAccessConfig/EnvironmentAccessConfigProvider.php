<?php namespace Rancherize\EnvironmentAccessConfig;

use Rancherize\Configuration\Services\GlobalConfiguration;
use Rancherize\Docker\DockerAccessService;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\RancherAccess\RancherAccessService;

/**
 * Class EnvironmentAccessConfigProvider
 *
 *
 *
 * @package Rancherize\EnvironmentAccessConfig
 */
class EnvironmentAccessConfigProvider implements Provider
{
    use ProviderTrait;

    /**
     */
    function register()
    {
        if (getenv('DOCKER_USER') && getenv('RANCHER_KEY')) {
            /**
             * Replace DockerAccessService
             * @param $c
             * @return DockerAccessEnvironmentService
             */
            $this->container[DockerAccessService::class] = function($c) {
                return new DockerAccessEnvironmentService($c['event']);
            };

            $this->container[RancherAccessService::class] = function() {
                return new RancherAccessEnvironmentService();
            };

            $this->container[GlobalConfiguration::class] = function($c) {
                return new \Rancherize\Configuration\Services\GlobalConfiguration(
                    new \Rancherize\Configuration\Loader\NullLoader(),
                    $c['writer']
                );
            };
        }
    }

    /**
     */
    function boot()
    {

    }
}