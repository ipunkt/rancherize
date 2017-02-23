<?php namespace Rancherize\Plugin\Exceptions;

use Rancherize\Exceptions\Exception;

/**
 * Class ComposerPacketNotRancherizePluginException
 * @package Rancherize\Plugin\Exceptions
 */
class ComposerPacketNotRancherizePluginException extends Exception {
	/**
	 * @var string
	 */
	private $packetName;
	/**
	 * @var string
	 */
	private $missingField;

	/**
	 * ComposerPacketNotRancherizePluginException constructor.
	 * @param string $packetName
	 * @param string $missingField
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct(string $packetName, string $missingField, $code = 0, \Exception $e = null) {
		$this->packetName = $packetName;
		$this->missingField = $missingField;
		parent::__construct("Packet $packetName is not a rancherize plugin. If you are the maintainer make sure to include $missingField in the composer.json", $code, $e);
	}
}