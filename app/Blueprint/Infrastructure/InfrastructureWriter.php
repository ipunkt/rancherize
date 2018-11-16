<?php namespace Rancherize\Blueprint\Infrastructure;

use Rancherize\Blueprint\Infrastructure\Copier\ServiceCopier;
use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Network\NetworkWriter;
use Rancherize\Blueprint\Infrastructure\Service\ServiceWriter;
use Rancherize\Blueprint\Infrastructure\Volume\VolumeWriter;
use Rancherize\File\FileWriter;

/**
 * Class InfrastructureWriter
 * @package Rancherize\Blueprint\Infrastructure
 *
 * Use
 */
class InfrastructureWriter {
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    protected $skipClear = false;
    /**
     * @var DockerfileWriter
     */
    private $dockerfileWriter;
    /**
     * @var ServiceWriter
     */
    private $serviceWriter;
    /**
     * @var VolumeWriter
     */
    private $volumeWriter;
    /**
     * @var NetworkWriter
     */
    private $networkWriter;
    /**
     * @var ServiceCopier
     */
    private $serviceCopier;

    /**
     * InfrastructureWriter constructor.
     * @param DockerfileWriter $dockerfileWriter
     * @param ServiceWriter $serviceWriter
     * @param VolumeWriter $volumeWriter
     * @param NetworkWriter $networkWriter
     * @param ServiceCopier $serviceCopier
     */
    public function __construct(
        DockerfileWriter $dockerfileWriter,
        ServiceWriter $serviceWriter,
        VolumeWriter $volumeWriter,
        NetworkWriter $networkWriter,
        ServiceCopier $serviceCopier
    ) {
        $this->dockerfileWriter = $dockerfileWriter;
        $this->serviceWriter = $serviceWriter;
        $this->volumeWriter = $volumeWriter;
        $this->networkWriter = $networkWriter;
        $this->serviceCopier = $serviceCopier;
    }

    /**
     * @param Infrastructure $infrastructure
     * @param FileWriter $fileWriter
     */
    public function write(Infrastructure $infrastructure, FileWriter $fileWriter)
    {

        $this->writeDockerfile($infrastructure, $fileWriter);

        $serviceWriter = $this->serviceWriter;
        $serviceWriter->setPath($this->path);
        $volumeWriter = $this->volumeWriter;
        $volumeWriter->setPath($this->path);
        $this->networkWriter->setPath($this->path);

        if (!$this->skipClear) {
            $serviceWriter->clear($fileWriter);
        }

        $this->copyVolumesFromForServices($infrastructure);

        foreach ($infrastructure->getServices() as $service) {
            $serviceWriter->write($service, $fileWriter);
        }

        foreach ($infrastructure->getVolumes() as $volume) {
            $volumeWriter->write($volume, $fileWriter);
        }

        foreach ($infrastructure->getNetworks() as $network) {
            $this->networkWriter->write($network, $fileWriter);
        }

    }

    /**
     * @param boolean $skipClear
     * @return InfrastructureWriter
     */
    public function setSkipClear(bool $skipClear): InfrastructureWriter
    {
        $this->skipClear = $skipClear;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param Infrastructure $infrastructure
     * @param FileWriter $fileWriter
     */
    private function writeDockerfile(Infrastructure $infrastructure, FileWriter $fileWriter)
    {
        if (!$infrastructure->hasDockerfile()) {
            return;
        }

        $dockerfileWriter = $this->dockerfileWriter;
        $dockerfileWriter->setPath($this->path);
        $dockerfileWriter->write($infrastructure->getDockerfile(), $fileWriter);
    }

    /**
     * @param Infrastructure $infrastructure
     */
    private function copyVolumesFromForServices(Infrastructure $infrastructure)
    {
        foreach ($infrastructure->getServices() as $service) {
            foreach ($service->getCopyVolumesFrom() as $copyFromService) {

                foreach ($copyFromService->getVolumes() as $volume) {
                    $service->addVolume($volume);
                }

                $copySidekicks = $copyFromService->getSidekicks();
                if (in_array($service, $copySidekicks)) {
                    foreach ($copyFromService->getVolumesFrom() as $volumesFromService) {
                        $service->addVolumeFrom($volumesFromService);
                    }
                } else {
                    foreach ($copyFromService->getVolumesFrom() as $volumesFromService) {

                        $copiedService = $this->serviceCopier->copy($volumesFromService);
                        $copiedService->setName(function () use ($volumesFromService, $service) {
                            return $volumesFromService->getName() . $service->getName();
                        });
                        $service->addVolumeFrom($copiedService);
                        $infrastructure->addService($copiedService);

                    }
                }

            }
        }
    }
}