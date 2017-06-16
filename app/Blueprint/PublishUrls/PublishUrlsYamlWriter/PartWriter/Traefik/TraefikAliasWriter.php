<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\PartWriter;

/**
 * Class TraefikAliasWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik
 */
class TraefikAliasWriter implements PartWriter {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 * @return mixed
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$url = $extraInformation->getUrl();
		$fullPath = parse_url($url, PHP_URL_HOST);
		$hostname = preg_match('[^\.]', $fullPath);

		$dockerService['labels']['traefik.alias'] = $hostname;
	}
}