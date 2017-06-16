<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\PartWriter;

/**
 * Class TraefikEnableWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik
 */
class TraefikEnableWriter implements PartWriter {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 * @return mixed
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {
		$dockerService['labels']['traefik.enable'] = 'true';
	}
}