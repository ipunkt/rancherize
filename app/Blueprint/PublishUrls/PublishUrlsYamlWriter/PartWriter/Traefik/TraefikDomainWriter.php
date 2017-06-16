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
	 * @return mixed
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$urls = $extraInformation->getUrls();
		$firstUrl = reset($urls);
		$fullPath = parse_url($firstUrl, PHP_URL_HOST);
		$hostname = preg_match('[^\.]', $fullPath);
		$domainName = substr($fullPath, count($hostname) + 2);

		$dockerService['labels']['traefik.domain'] = $domainName;
	}
}