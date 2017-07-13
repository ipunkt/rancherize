<?php namespace Rancherize\Blueprint\NginxSnippets\NginxSnippetInfrastructureEventHandler;

use Rancherize\Blueprint\NginxSnippets\NginxSnippetService\NginxSnippetService;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;

/**
 * Class NginxSnippetInfrastructureEventHandler
 * @package Rancherize\Blueprint\NginxSnippets\NginxSnippetInfrastructureEventHandler
 */
class NginxSnippetInfrastructureEventHandler {
	/**
	 * @var NginxSnippetService
	 */
	private $snippetService;

	/**
	 * NginxSnippetInfrastructureEventHandler constructor.
	 * @param NginxSnippetService $snippetService
	 */
	public function __construct( NginxSnippetService $snippetService) {
		$this->snippetService = $snippetService;
	}

	/**
	 * @param InfrastructureBuiltEvent $event
	 */
	public function infrastructureBuilt( InfrastructureBuiltEvent $event ) {
		$infrastructure = $event->getInfrastructure();

		foreach($infrastructure->getServices() as $service)
			$this->snippetService->addToInfrastructure($infrastructure, $service);

		$event->setInfrastructure($infrastructure);
	}
}