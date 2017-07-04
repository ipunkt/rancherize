<?php namespace Rancherize\Blueprint\ExternalService\EventListener;

use Rancherize\Blueprint\ExternalService\ExternalServiceParser\ExternalServiceNameParser;
use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;

/**
 * Class ExternalServicePushListener
 * @package Rancherize\Blueprint\ExternalService\EventListener
 */
class ExternalServicePushListener {
	/**
	 * @var ExternalServiceNameParser
	 */
	private $nameParser;

	/**
	 * ExternalServicePushListener constructor.
	 * @param ExternalServiceNameParser $nameParser
	 */
	public function __construct( ExternalServiceNameParser $nameParser) {
		$this->nameParser = $nameParser;
	}

	/**
	 * @param PushCommandInServiceUpgradeEvent $event
	 */
	public function inServiceUpgrade( PushCommandInServiceUpgradeEvent $event ) {

		$configuration = $event->getConfiguration();

		$serviceNames = $event->getServiceNames();
		$externalServiceNames = $this->nameParser->parseNames( $configuration );

		$allServicesNames = array_merge($externalServiceNames, $serviceNames);
		$event->setServiceNames($allServicesNames);
		//$event->setForceUpgrade( true );

	}

	/**
	 * @param PushCommandStartEvent $event
	 */
	public function start( PushCommandStartEvent $event ) {

		$configuration = $event->getConfiguration();

		$serviceNames = $event->getServiceNames();
		$externalServiceNames = $this->nameParser->parseNames( $configuration );

		$allServicesNames = array_merge($externalServiceNames, $serviceNames);
		$event->setServiceNames($allServicesNames);
		//$event->setForceUpgrade( true );

	}
}