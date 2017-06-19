<?php namespace Rancherize\Blueprint\Scheduler\SchedulerYamlWriter;

use Rancherize\Blueprint\Scheduler\SchedulerExtraInformation\SchedulerExtraInformation;

/**
 * Class SchedulerYamlWriter
 * @package Rancherize\Blueprint\Scheduler\SchedulerYamlWriter
 */
class SchedulerYamlWriter {

	/**
	 * @var string
	 */
	protected $defaultScheduler = 'rancher';

	/**
	 * @var string
	 */
	protected $defaultVersion = '2';

	/**
	 * @var SchedulerWriterVersion[][]
	 */
	protected $versions = [
		'rancher' => [
		],
	];

	public function __construct() {
		$this->versions['rancher']['2'] = container('v2-rancher-scheduler-yaml-writer');
	}

	/**
	 * @param $fileVersion
	 * @param SchedulerExtraInformation $information
	 * @param $dockerContent
	 */
	public function write( $fileVersion, SchedulerExtraInformation $information, &$dockerContent ) {
		$schedulerName = $information->getScheduler();
		if( !array_key_exists($schedulerName, $this->versions) )
			$schedulerName = $this->defaultScheduler;

		if( !array_key_exists($fileVersion, $this->versions[$schedulerName]) )
			$fileVersion = $this->defaultVersion;

		$scheduler = $this->versions[$schedulerName][$fileVersion];

		$scheduler->write($information, $dockerContent);
	}
}