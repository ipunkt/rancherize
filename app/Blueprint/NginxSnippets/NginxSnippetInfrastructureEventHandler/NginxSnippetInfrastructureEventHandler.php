<?php namespace Rancherize\Blueprint\NginxSnippets\NginxSnippetInfrastructureEventHandler;

use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\NginxSnippets\NginxSnippetExtraInformation\NginxSnippetExtraInformation;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;

/**
 * Class NginxSnippetInfrastructureEventHandler
 * @package Rancherize\Blueprint\NginxSnippets\NginxSnippetInfrastructureEventHandler
 */
class NginxSnippetInfrastructureEventHandler {

	/**
	 * @param InfrastructureBuiltEvent $event
	 */
	public function infrastructureBuilt( InfrastructureBuiltEvent $event ) {
		$infrastructure = $event->getInfrastructure();

		foreach($infrastructure->getServices() as $service)
			$this->addService($service);

		$event->setInfrastructure($infrastructure);
	}

	/**
	 * @param Service $service
	 */
	private function addService( Service $service ) {
		try {
			$information = $service->getExtraInformation(NginxSnippetExtraInformation::IDENTIFIER);
		} catch(ExtraInformationNotFoundException $e) {
			return;
		}

		if( !$information instanceof NginxSnippetExtraInformation )
			return;

		$snippets = $information->getSnippets();
		if( empty($snippets) )
			return;

		foreach($snippets as $snippet) {
		}
	}
}