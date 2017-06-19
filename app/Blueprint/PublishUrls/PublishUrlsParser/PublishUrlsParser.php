<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsParser;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class PublishUrlsParser
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsParser
 *
 * Reads urls to be published from the configuration
 */
class PublishUrlsParser {

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 */
	public function parseToService( Service $service, Configuration $configuration ) {

		$publishUrlsConfig = new PrefixConfigurationDecorator($configuration, 'publish-urls.');

		$publishUrlsEnabled = $publishUrlsConfig->get( 'enable', true );
		if( !$publishUrlsEnabled )
			return;

		$publishUrlsInformation = new PublishUrlsExtraInformation();
		$publishUrlsInformation->setPort( $publishUrlsConfig->get('port', 80) );
		$publishUrlsInformation->setType( $publishUrlsConfig->get('type', 'traefik') );

		$url = $publishUrlsConfig->get( 'url', '' );
		if( empty($url) )
			return;
		$publishUrlsInformation->setUrl( $url );

		$pathes = $publishUrlsConfig->get( 'pathes', [] );
		$publishUrlsInformation->setPathes($pathes);

		$defaultPriority = 5;
		if( !empty($pathes) )
			$defaultPriority = 10;
		$publishUrlsInformation->setPriority( $publishUrlsConfig->get('priority', $defaultPriority) );

		$service->addExtraInformation( $publishUrlsInformation );
	}

}