<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;

/**
 * Interface PartWriter
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2
 */
interface PartWriter {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService );
}