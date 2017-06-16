<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter;

use Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation\PublishUrlsExtraInformation;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik\TraefikAliasWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik\TraefikDomainWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik\TraefikEnableWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik\TraefikPathesWriter;
use Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter\PartWriter\Traefik\TraefikPriorityWriter;

/**
 * Class V2TraefikPublishUrlsYamlWriterVersion
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsYamlWriter
 */
class V2TraefikPublishUrlsYamlWriterVersion extends PublishUrlsYamlWithPartWriters {

	public function __construct() {
		$this->addPartWriter( new TraefikEnableWriter() );
		$this->addPartWriter( new TraefikAliasWriter() );
		$this->addPartWriter( new TraefikDomainWriter() );
		$this->addPartWriter( new TraefikPathesWriter() );
		$this->addPartWriter( new TraefikPriorityWriter() );
	}

	/**
	 * @param PublishUrlsExtraInformation $extraInformation
	 * @param array $dockerService
	 */
	public function write( PublishUrlsExtraInformation $extraInformation, array &$dockerService ) {

		if( !array_key_exists('labels', $dockerService) )
			$dockerService['labels'] = [];

		parent::write($extraInformation, $dockerService);

	}
}