<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\Modes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\CpuLimitMode;

/**
 * Class HighCpuMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\Modes
 */
class HighCpuMode implements CpuLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setCpuPeriod(1000);
		$extraInformation->setCpuQuota(700);
	}
}