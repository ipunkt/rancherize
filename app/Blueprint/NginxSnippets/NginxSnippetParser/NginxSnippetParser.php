<?php namespace Rancherize\Blueprint\NginxSnippets\NginxSnippetParser;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\NginxSnippets\NginxSnippetExtraInformation\NginxSnippetExtraInformation;
use Rancherize\Configuration\Configuration;

/**
 * Class NginxSnippetParser
 * @package Rancherize\Blueprint\NginxSnippets\NginxSnippetParser
 */
class NginxSnippetParser {

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 */
	public function parse( Service $service, Configuration $configuration ) {
		if( !$configuration->has('nginx.snippets') )
			return;

		if( !$configuration->get('nginx.enable', true) )
			return;

		$extraInformation = new NginxSnippetExtraInformation();
		$snippets = $configuration->get('nginx.snippets', []);
		if( empty($snippets) )
			return;

		foreach( $snippets as $snippet )
			$extraInformation->addSnippet($snippet);

		$service->addExtraInformation( $extraInformation );
	}

}