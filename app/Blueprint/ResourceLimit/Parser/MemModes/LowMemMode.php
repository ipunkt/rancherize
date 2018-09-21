<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\MemModes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\MemLimitMode;

/**
 * Class LowMemMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\MemModes
 */
class LowMemMode implements MemLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryLimit('512m' );
	}

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setReservation( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryReservation('256m');
	}
}