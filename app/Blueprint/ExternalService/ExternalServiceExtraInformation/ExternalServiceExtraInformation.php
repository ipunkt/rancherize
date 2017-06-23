<?php namespace Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Class ExternalServiceExtraInformation
 * @package Rancherize\Blueprint\ExternalService\ExternalServiceExtraInformation
 */
class ExternalServiceExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'external-service';

	/**
	 * @var string[]
	 */
	protected $externalIps = [];

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return self::IDENTIFIER;
	}

	/**
	 * @return \string[]
	 */
	public function getExternalIps() {
		return $this->externalIps;
	}

	/**
	 * @param \string[] $externalIps
	 */
	public function setExternalIps( array $externalIps ) {
		$this->externalIps = $externalIps;
	}
}