<?php namespace Rancherize\Plugin\Composer;

/**
 * Interface ComposerPacket
 * @package Plugin\Composer
 */
interface ComposerPacket {

	/**
	 * @return string
	 */
	function getNamespace();

	/**
	 * @return string
	 */
	function getName();

	/**
	 * @return mixed
	 */
	function getVersion();

}