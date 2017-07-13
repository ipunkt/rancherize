<?php namespace Rancherize\Blueprint\NginxSnippets\NginxSnippetService;

use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\NginxSnippets\NginxSnippetExtraInformation\NginxSnippetExtraInformation;

/**
 * Class NginxSnippetService
 * @package Rancherize\Blueprint\NginxSnippets\NginxSnippetService
 */
class NginxSnippetService {

	/**
	 * @param Infrastructure $infrastructure
	 * @param Service $service
	 */
	public function addToInfrastructure( Infrastructure $infrastructure, Service $service ) {
		$dockerfile = $infrastructure->getDockerfile();

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

		if( $information->isMountWorkdir() ) {

			$this->addSnippetsToService( $service, $snippets );

			return;
		}

		$this->addSnippetsToImage( $dockerfile, $snippets );

	}

	/**
	 * @param $dockerfile
	 * @param $snippets
	 */
	protected function addSnippetsToImage( Dockerfile $dockerfile, $snippets ) {
		$dockerfile->addVolume( '/etc/nginx/server.d' );
		foreach ( $snippets as $snippet )
			$dockerfile->copy( $snippet, '/etc/nginx/server.d/' );
	}

	/**
	 * @param Service $service
	 * @param $snippets
	 */
	protected function addSnippetsToService( Service $service, $snippets ) {
		foreach ( $snippets as $snippet ) {
			$filename = basename( $snippet );
			$service->addVolume( getcwd() . DIRECTORY_SEPARATOR . $snippet, '/etc/nginx/server.d/' . $filename );

		}
	}
}