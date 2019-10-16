<?php namespace Rancherize\Blueprint\Infrastructure\Service;

/**
 * Class ServiceYamlDefinition
 * @package Rancherize\Blueprint\Infrastructure\Service
 */
class ServiceYamlDefinition
{

    /**
     * @var array
     */
    public $dockerComposeEntry = [];

    /**
     * @var array
     */
    public $rancherComposeEntry = [];

    /**
     * @var array
     */
    public $volumeDefinition = [];

    /**
     * @param $dockerComposeEntry
     * @param $rancherComposeEntry
     * @param $volumeDefinition
     * @return ServiceYamlDefinition
     */
    static function make($dockerComposeEntry, $rancherComposeEntry, $volumeDefinition) {
        $definition = new self;
        $definition->dockerComposeEntry = $dockerComposeEntry;
        $definition->rancherComposeEntry = $rancherComposeEntry;
        $definition->volumeDefinition = $volumeDefinition;
        return $definition;
    }

}