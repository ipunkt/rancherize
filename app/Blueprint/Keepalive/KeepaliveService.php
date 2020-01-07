<?php namespace Rancherize\Blueprint\Keepalive;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckDefaultInformationSetter;
use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;
use Rancherize\Blueprint\Infrastructure\Service\NetworkMode\ShareNetworkMode;
use Rancherize\Blueprint\Infrastructure\Service\Service;

/**
 * Class KeepaliveService
 * @package Rancherize\Blueprint\Keepalive
 */
class KeepaliveService extends Service
{

    public function __construct() {
        parent::__construct();

        $this->image = 'busybox';
        $this->command = 'httpd -f';
        $this->tty = false;
        $this->keepStdin = false;

        $this->setHealthcheck();
    }

    private function setHealthcheck()
    {
        /**
         * @var HealthcheckDefaultInformationSetter $defaultSetter
         */
        $defaultSetter = container(HealthcheckDefaultInformationSetter::class);

        $healthcheckInformation = new HealthcheckExtraInformation();
        $defaultSetter->setDefaults($healthcheckInformation);
        $healthcheckInformation->setPort(80);
        $healthcheckInformation->setInterval(10000);
        $this->addExtraInformation($healthcheckInformation);
    }

    /**
     * @var Service
     */
    protected $targetService;

    /**
     * @param Service $targetService
     * @return KeepaliveService
     */
    public function setTargetService(Service $targetService): KeepaliveService
    {
        $this->targetService = $targetService;
        return $this;
    }

    public function takeOver()
    {

        $targetService = $this->targetService;
        $this->setName(function() use ($targetService) {
            return 'KA-'.$targetService->getName();
        });

        $this->externalLinks = $targetService->externalLinks;
        $this->links = $targetService->links;
        $this->copySidekicks($this->targetService);
        $this->addSidekick($this->targetService);

        $this->copyVolumesFrom = $this->targetService->copyVolumesFrom;
        $this->targetService->copyVolumesFrom = [];
        $this->targetService->addCopyVolumesFrom($this);

        $this->copyLabels($this->targetService);
        $this->targetService->setNetworkMode( new ShareNetworkMode($this) );
        $targetService->setMantleService($this);
    }

}