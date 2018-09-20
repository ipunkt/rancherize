<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\MemModes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\MemLimitMode;

/**
 * Class FullMemMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\MemModes
 */
class FullMemMode implements MemLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryLimit( null );
	}

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setReservation( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryReservation( null );
	}
}