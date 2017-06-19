<?php namespace Rancherize\Blueprint\Scheduler\SchedulerYamlWriter;
use Rancherize\Blueprint\Scheduler\SchedulerExtraInformation\SchedulerExtraInformation;

/**
 * Interface SchedulerWriterVersion
 * @package Rancherize\Blueprint\Scheduler\SchedulerYamlWriter
 */
interface SchedulerWriterVersion {

	/**
	 * @param SchedulerExtraInformation $information
	 * @param $dockerContent
	 */
	function write(SchedulerExtraInformation $information, &$dockerContent);

}