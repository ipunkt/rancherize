<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class PublishUrlsExtraInformation
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation
 */
class PublishUrlsExtraInformation implements ServiceExtraInformation {

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var
	 */
	protected $urls = [];

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return 'publish-urls';
	}

	/**
	 * @return int
	 */
	public function getPort(): int {
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort( int $port ) {
		$this->port = $port;
	}

	/**
	 * @return mixed
	 */
	public function getUrls() {
		return $this->urls;
	}

	/**
	 * @param mixed $urls
	 */
	public function setUrls( $urls ) {
		$this->urls = $urls;
	}
}