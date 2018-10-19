<?php namespace Rancherize\Services;

use Rancherize\Plugin\ProviderTrait;
use Rancherize\Services\UnitConversionService\UnitConversionService;

/**
 * Class Provider
 * @package Rancherize\Services
 */
class Provider implements \Rancherize\Plugin\Provider {

    use ProviderTrait;

    /**
     */
    public function register() {
        $this->container[UnitConversionService::class] = function() {
            return new UnitConversionService();
        };
    }

    /**
     */
    public function boot() {
    }
}