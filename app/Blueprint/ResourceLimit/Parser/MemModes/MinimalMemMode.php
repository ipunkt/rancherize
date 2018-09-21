<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\MemModes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\MemLimitMode;

/**
 * Class MinimalMemMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\MemModes
 */
class MinimalMemMode implements MemLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryLimit('128M');
	}

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setReservation( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryReservation('64m');
	}
}