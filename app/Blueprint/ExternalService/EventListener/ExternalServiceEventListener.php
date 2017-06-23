<?php namespace Rancherize\Blueprint\ExternalService\EventListener;

use Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation\ExternalServiceExtraInformation;
use Rancherize\Blueprint\ExternalService\ExternalServiceYamlWriter\ExternalServiceYamlWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;

/**
 * Class ExternalServiceEventListener
 * @package Rancherize\Blueprint\ExternalService\EventListener
 */
class ExternalServiceEventListener {
	/**
	 * @var ExternalServiceYamlWriter
	 */
	private $yamlWriter;

	/**
	 * ExternalServiceEventListener constructor.
	 * @param ExternalServiceYamlWriter $yamlWriter
	 */
	public function __construct( ExternalServiceYamlWriter $yamlWriter) {
		$this->yamlWriter = $yamlWriter;
	}

	/**
	 *
	 */
	public function servicePrepared( ServiceWriterServicePreparedEvent $event ) {

		$rancherData = $event->getRancherContent();

		$fileVersion = $event->getFileVersion();
		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation(ExternalServiceExtraInformation::IDENTIFIER);
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( ! $extraInformation instanceof ExternalServiceExtraInformation )
			return;

		$this->yamlWriter->write( $fileVersion, $extraInformation, $rancherData );

		$event->setRancherContent($rancherData);

	}

}