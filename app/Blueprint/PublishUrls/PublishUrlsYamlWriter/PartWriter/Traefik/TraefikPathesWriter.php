<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\PartWriter;

/**
 * Class TraefikPathesWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik
 */
class TraefikPathesWriter implements PartWriter {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 * @return mixed
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$urls = $extraInformation->getUrls();

		$pathes = [];
		foreach ($urls as $url) {
			$path = parse_url($url, PHP_URL_PATH);

			if( empty($path) || $path === '/' )
				continue;

			$pathes[] = $path;
		}

		if( empty($pathes) )
			return;

		$dockerService['labels']['traefik.path.prefix'] = implode(',', $pathes);
	}
}