<?php namespace Rancherize\Blueprint\Infrastructure\Service\Events;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ServiceWriterServicePreparedEvent
 * @package Rancherize\Blueprint\Infrastructure\Service\Events
 */
class ServiceWriterServicePreparedEvent extends Event {

	const NAME = 'service-writer.write';

	/**
	 * @var Service
	 */
	private $service;

	/**
	 * @var array
	 */
	private $dockerContent;

	/**
	 * @var
	 */
	private $rancherContent;

	/**
	 * @var array
	 */
	private $volumeDefinition;

	/**
	 * @var int
	 */
	private $fileVersion = 2;

	/**
	 * ServiceWriterServicePreparedEvent constructor.
	 * @param Service $service
	 * @param $rancherContent
	 */
	public function __construct( Service $service, &$rancherContent) {
		$this->service = $service;
		$this->rancherContent = $rancherContent;
	}

	/**
	 * @return Service
	 */
	public function getService() {
		return $this->service;
	}

	/**
	 * @return array
	 */
	public function getRancherContent() {
		return $this->rancherContent;
	}

	/**
	 * @param mixed $rancherContent
	 */
	public function setRancherContent( $rancherContent ) {
		$this->rancherContent = $rancherContent;
	}

	/**
	 * @return int
	 */
	public function getFileVersion() {
		return $this->fileVersion;
	}

	/**
	 * @return array
	 */
	public function getDockerContent() {
		return $this->dockerContent;
	}

	/**
	 * @param array $dockerContent
	 */
	public function setDockerContent( array $dockerContent ) {
		$this->dockerContent = $dockerContent;
	}

	/**
	 * @return array
	 */
	public function getVolumeDefinition() {
		return $this->volumeDefinition;
	}

	/**
	 * @param array $volumeDefinition
	 */
	public function setVolumeDefinition( array $volumeDefinition ) {
		$this->volumeDefinition = $volumeDefinition;
	}

}