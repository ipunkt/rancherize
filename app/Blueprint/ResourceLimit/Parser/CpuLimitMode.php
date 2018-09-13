<?php namespace Rancherize\Blueprint\ResourceLimit\Parser;

use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;

/**
 * Interface CpuLimitMode
 * @package Rancherize\Blueprint\ResourceLimit\Parser
 */
interface CpuLimitMode {

	/**
	 * @param ResourceLimitExtraInformation $extraInformation
	 */
	function setLimit(ResourceLimitExtraInformation $extraInformation);

}