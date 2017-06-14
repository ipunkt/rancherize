<?php namespace Rancherize\Blueprint\Infrastructure\Service\Events;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ServiceWriterRancherServicePreparedEvent
 * @package Rancherize\Blueprint\Infrastructure\Service\Events
 */
class ServiceWriterRancherServicePreparedEvent extends Event {

	const NAME = 'service-writer.write';
	/**
	 * @var Service
	 */
	private $service;

	/**
	 * @var
	 */
	private $rancherContent;

	/**
	 * @var int
	 */
	private $fileVersion = 2;

	/**
	 * ServiceWriterRancherServicePreparedEvent constructor.
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

}