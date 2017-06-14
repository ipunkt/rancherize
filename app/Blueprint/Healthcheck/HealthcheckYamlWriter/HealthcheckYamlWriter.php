<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter;
use Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation\HealthcheckExtraInformation;

/**
 * Class HealthcheckYamlWriter
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckYamlWriter
 *
 * This class is a dispatcher which defers the write calls to the HealthcheckYamlWriterVersion for the specified file version.
 */
class HealthcheckYamlWriter {

	protected  $defaultVersion = 2;

	/**
	 * @var HealthcheckYamlWriterVersion[]
	 */
	protected $versions = [];

	public function __construct() {
		$this->versions[2] = new V2HealthcheckYamlWriter();
	}

	/**
	 * @param $version
	 * @param HealthcheckExtraInformation $extraInformation
	 * @param array $rancherService
	 */
	public function write( $version, HealthcheckExtraInformation $extraInformation, array &$rancherService ) {
		$implementation = $this->getWriter($version);

		$implementation->write($extraInformation, $rancherService);
	}

	/**
	 * @param $version
	 * @return HealthcheckYamlWriterVersion
	 */
	protected function getWriter($version) {
		$defaultVersion = $this->defaultVersion;

		if( !array_key_exists($version, $this->versions) )
			return $this->versions[$defaultVersion];

		return $this->versions[$version];
	}

}