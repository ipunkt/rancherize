<?php namespace Rancherize\Blueprint\PublisUrls\EventListener;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PublishUrlsYamlWriter;

/**
 * Class HealthcheckServiceWriterListener
 * @package Rancherize\Blueprint\Healthcheck\EventListener
 */
class PublishUrlsServiceWriterListener {
	/**
	 * @var PublishUrlsYamlWriter
	 */
	private $yamlWriter;

	/**
	 * PublishUrlsServiceWriterListener constructor.
	 * @param PublishUrlsYamlWriter $yamlWriter
	 */
	public function __construct( PublishUrlsYamlWriter $yamlWriter ) {
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
			$extraInformation = $service->getExtraInformation(PublishUrlsExtraInformation::IDENTIFIER );
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( !$extraInformation instanceof PublishUrlsExtraInformation )
			return;

		$this->yamlWriter->write( $fileVersion, $extraInformation, $dockerContent );

		$event->setDockerContent($dockerContent);
	}
}