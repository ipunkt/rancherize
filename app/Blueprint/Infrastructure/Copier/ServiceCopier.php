<?php namespace Rancherize\Blueprint\Infrastructure\Copier;

use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class ServiceCopier
 * @package Rancherize\Blueprint\Infrastructure\Copier
 */
class ServiceCopier
{

    /**
     * @param Service $service
     * @return Service
     */
    public function copy(Service $service)
    {

        $newService = new Service();
        $newService->setName($service->getName());
        $newService->setImage($service->getImage());
        $newService->setRestart($service->getRestart());
        $newService->setCommand($service->getCommand());
        $newService->setKeepStdin($service->isKeepStdin());

        return $newService;
    }

}