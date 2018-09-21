<?php namespace Rancherize\Blueprint\ResourceLimit\Parser\MemModes;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Blueprint\ResourceLimit\Parser\MemLimitMode;

/**
 * Class HighMemMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser\MemModes
 */
class HighMemMode implements MemLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setLimit( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryLimit('15036m');
	}

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	public function setReservation( ResourceLimitExtraInformation $extraInformation ) {
		$extraInformation->setMemoryReservation('1024m' );
	}
}