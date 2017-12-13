<?php namespace Rancherize\Blueprint\Infrastructure\Network;

/**
 * Class Network
 * @package Rancherize\Blueprint\Infrastructure\Network
 */
class Network {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var bool
	 */
	protected $external;

	/**
	 * @var string
	 */
	protected $externalName;

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function isExternal(): bool {
		return $this->external;
	}

	/**
	 * @param bool $external
	 */
	public function setExternal( bool $external ) {
		$this->external = $external;
	}

	/**
	 * @return string
	 */
	public function getExternalName(): string {
		return $this->externalName;
	}

	/**
	 * @param string $externalName
	 */
	public function setExternalName( string $externalName ) {
		$this->externalName = $externalName;
	}

}