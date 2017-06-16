<?php namespace Rancherize\Blueprint\PublisUrls\EventListener;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;
use Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter\HealthcheckYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterRancherServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;

/**
 * Class HealthcheckServiceWriterListener
 * @package Rancherize\Blueprint\Healthcheck\EventListener
 */
class PublishUrlsServiceWriterListener {

	/**
	 * @var HealthcheckYamlWriter
	 */
	private $healthcheckYamlWriter;

	/**
	 * HealthcheckServiceWriterListener constructor.
	 * @param HealthcheckYamlWriter $healthcheckYamlWriter
	 */
	public function __construct( HealthcheckYamlWriter $healthcheckYamlWriter) {
		$this->healthcheckYamlWriter = $healthcheckYamlWriter;
	}

	/**
	 * @param ServiceWriterRancherServicePreparedEvent $event
	 */
	public function rancherServicePrepared( ServiceWriterRancherServicePreparedEvent $event ) {
		$rancherData = $event->getRancherContent();

		$fileVersion = $event->getFileVersion();
		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation(HealthcheckExtraInformation::IDENTIFIER);
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( !$extraInformation instanceof HealthcheckExtraInformation )
			return;

		$this->healthcheckYamlWriter->write( $fileVersion, $extraInformation, $rancherData );

		$event->setRancherContent($rancherData);
	}
}