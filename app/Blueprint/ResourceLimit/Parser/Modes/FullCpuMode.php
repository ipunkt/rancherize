<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\Modes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\CpuLimitMode;

/**
 * Class FullCpuMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\Modes
 */
class FullCpuMode implements CpuLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setCpuPeriod(null);
		$extraInformation->setCpuQuota(null);
	}
}