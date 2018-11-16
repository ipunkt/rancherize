<?php namespace Rancherize\Blueprint\DataImages\Parser;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;
use Rancherize\Docker\NameCleaner\NameCleaner;

/**
 * Class Parser
 * @package Rancherize\Blueprint\DataImages\Parser
 */
class Parser
{
    /**
     * @var NameCleaner
     */
    private $nameCleaner;

    /**
     * Parser constructor.
     * @param NameCleaner $nameCleaner
     */
    public function __construct(NameCleaner $nameCleaner)
    {
        $this->nameCleaner = $nameCleaner;
    }

    /**
     * @param Configuration $configuration
     * @param Service $mainService
     * @param Infrastructure $infrastructure
     */
    public function parse(Configuration $configuration, Service $mainService, Infrastructure $infrastructure)
    {

        if (!$configuration->has('data-images')) {
            return;
        }

        $dataImages = $configuration->get('data-images');
        if (!is_array($dataImages)) {
            return;
        }

        foreach ($dataImages as $dataImage) {
            $service = new Service();
            $service->setName($this->nameCleaner->cleanName($dataImage));
            $service->setImage($dataImage);
            $infrastructure->addService($mainService);
            $mainService->addSidekick($service);
        }
    }

}