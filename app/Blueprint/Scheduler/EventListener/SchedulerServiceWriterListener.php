<?php namespace Rancherize\Blueprint\Scheduler\EventListener;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\Scheduler\SchedulerExtraInformation\SchedulerExtraInformation;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\SchedulerYamlWriter;

/**
 * Class HealthcheckServiceWriterListener
 * @package Rancherize\Blueprint\Healthcheck\EventListener
 */
class SchedulerServiceWriterListener {
	/**
	 * @var SchedulerYamlWriter
	 */
	private $yamlWriter;

	/**
	 * PublishUrlsServiceWriterListener constructor.
	 * @param SchedulerYamlWriter $yamlWriter
	 */
	public function __construct( SchedulerYamlWriter $yamlWriter ) {
		$this->yamlWriter = $yamlWriter;
	}

	/**
	 * @param ServiceWriterServicePreparedEvent $event
	 */
	public function servicePrepared( ServiceWriterServicePreparedEvent $event ) {
		$dockerContent = $event->getDockerContent();

		$fileVersion = $event->getFileVersion();
		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation(SchedulerExtraInformation::IDENTIFIER );
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( !$extraInformation instanceof SchedulerExtraInformation )
			return;

		$this->yamlWriter->write( $fileVersion, $extraInformation, $dockerContent );

		$event->setDockerContent($dockerContent);
	}
}