<?php namespace Rancherize\Blueprint\ResourceLimit\Parser;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;

/**
 * Interface MemLimitMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser
 */
interface MemLimitMode {


	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	function setLimit(ResourceLimitExtraInformation $extraInformation);

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	function setReservation(ResourceLimitExtraInformation $extraInformation);

}