<?php namespace Rancherize\Blueprint\Infrastructure\Service\NetworkMode;

/**
 * Class NoneNetworkMode
 * @package Rancherize\Blueprint\Infrastructure\Service\NetworkMode
 */
class NoNetworkMode implements NetworkMode
{

    /**
     * @return string
     */
    public function getNetworkMode(): string
    {
        return 'none';
    }
}