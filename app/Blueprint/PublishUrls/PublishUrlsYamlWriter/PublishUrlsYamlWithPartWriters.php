<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\Traefik\V2\PartWriter;

/**
 * Class PublishUrlsYamlWithPartWriters
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter
 */
class PublishUrlsYamlWithPartWriters implements PublishUrlsYamlWriterVersion {

	/**
	 * @var PartWriter[]
	 */
	protected $partWriters;

	/**
	 * @param PartWriter $partWriter
	 */
	protected function addPartWriter(PartWriter $partWriter) {
		$this->partWriters[] = $partWriter;
	}

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {

		foreach($this->partWriters as $partWriter)
			$partWriter->write($extraInformation, $dockerService);
	}
}