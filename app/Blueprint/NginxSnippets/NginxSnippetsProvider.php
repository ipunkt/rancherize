<?php namespace Rancherize\Blueprint\NginxSnippets;

use Rancherize\Blueprint\NginxSnippets\NginxSnippetInfrastructureEventHandler\NginxSnippetInfrastructureEventHandler;
use Rancherize\Blueprint\NginxSnippets\NginxSnippetParser\NginxSnippetParser;
use Rancherize\Plugin\Provider;
use Rancherize\Plugin\ProviderTrait;
use Rancherize\Services\BuildServiceEvent\InfrastructureBuiltEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class NginxSnippetsProvider
 * @package Rancherize\Blueprint\NginxSnippets
 */
class NginxSnippetsProvider implements Provider {

	use ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['nginx-snippets-parser'] = function($c) {
			return new NginxSnippetParser();
		};
		$this->container['nginx-infrastructure-built-listener'] = function($c) {
			return new NginxSnippetInfrastructureEventHandler();
		};
	}

	/**
	 */
	public function boot() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = $this->container['event'];
		$listener = $this->container['nginx-infrastructure-built-listener'];
		$event->addListener(InfrastructureBuiltEvent::NAME, [$listener, 'infrastructureBuilt']);
	}
}