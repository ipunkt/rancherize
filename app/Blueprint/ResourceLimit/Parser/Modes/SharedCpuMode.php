<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\Modes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\CpuLimitMode;

/**
 * Class SharedImportantCpuMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\Modes
 */
class SharedCpuMode implements CpuLimitMode
{

    /**
     * @param ResourceLimitExtraInformation $extraInformation
     */
    public function setLimit(ResourceLimitExtraInformation $extraInformation)
    {
    }

    /**
     * @param ResourceLimitExtraInformation $extraInformation
     */
    public function setReservation(ResourceLimitExtraInformation $extraInformation)
    {
        $extraInformation->setCpuShares(1024);
    }
}