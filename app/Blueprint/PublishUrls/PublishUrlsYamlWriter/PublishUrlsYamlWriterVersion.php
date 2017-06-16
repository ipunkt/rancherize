<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;

/**
 * Interface PublishUrlsYamlWriterVersion
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter
 */
interface PublishUrlsYamlWriterVersion {

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService );
}