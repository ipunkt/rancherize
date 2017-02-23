<?php namespace Rancherize\Plugin\Composer;

/**
 * Class PODComposerPacket
 * @package Rancherize\Plugin\Composer
 */
class PODComposerPacket implements ComposerPacket {

	/**
	 * @var string
	 */
	private $version = '';

	/**
	 * @var string
	 */
	private $name = '';

	/**
	 * @var string
	 */
	private $namespace = '';

	/**
	 * @return string
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion(string $version) {
		$this->version = $version;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
	}

	/**
	 * @param string $namespace
	 */
	public function setNamespace(string $namespace) {
		$this->namespace = $namespace;
	}

}