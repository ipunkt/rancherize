<?php namespace Rancherize\Blueprint\Healthcheck\EventListener;

use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;
use Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter\HealthcheckYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;

/**
 * Class HealthcheckServiceWriterListener
 * @package Rancherize\Blueprint\Healthcheck\EventListener
 */
class HealthcheckServiceWriterListener {
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
	 * @param ServiceWriterServicePreparedEvent $event
	 */
	public function servicePrepared( ServiceWriterServicePreparedEvent $event ) {
		$rancherData = $event->getRancherContent();

		$fileVersion = $event->getFileVersion();
		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation(HealthcheckExtraInformation::IDENTIFIER);
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( ! $extraInformation instanceof HealthcheckExtraInformation )
			return;

		$this->healthcheckYamlWriter->write( $fileVersion, $extraInformation, $rancherData );

		$event->setRancherContent($rancherData);
	}
}