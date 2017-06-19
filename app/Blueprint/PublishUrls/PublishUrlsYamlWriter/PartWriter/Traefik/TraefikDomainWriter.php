<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\PartWriter;

/**
 * Class TraefikDomainWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik
 */
class TraefikDomainWriter implements PartWriter {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$url = $extraInformation->getUrl();
		$fullPath = parse_url($url, PHP_URL_HOST);

		$matches = [];
		if( preg_match('/[^\.]*/', $fullPath, $matches) !== 1 )
			return;
		$hostname = $matches[0];

		$domainName = substr($fullPath, strlen($hostname) + 2);

		$dockerService['labels']['traefik.domain'] = $domainName;
	}
}