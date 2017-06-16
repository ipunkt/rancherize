<?php namespace Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class PublishUrlsExtraInformation
 * @package Rancherize\Blueprint\PublishUrls\PublishUrlsExtraInformation
 */
class PublishUrlsExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'publish-urls';

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var
	 */
	protected $urls = [];

	/**
	 * @var int
	 */
	protected $priority;

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return 'publish-urls';
	}

	/**
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort( $port ) {
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

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param int $priority
	 */
	public function setPriority( $priority ) {
		$this->priority = $priority;
	}
}