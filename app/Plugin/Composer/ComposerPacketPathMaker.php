<?php namespace Rancherize\Plugin\Composer;

/**
 * Class ComposerPacketPathMaker
 * @package Plugin\Installer
 */
class ComposerPacketPathMaker {

	/**
	 * @param ComposerPacket $packet
	 * @return string
	 */
	public function makePath(ComposerPacket $packet) {

		$namespace = $packet->getNamespace();
		$name = $packet->getName();

		return "vendor/$namespace/$name";
	}

}