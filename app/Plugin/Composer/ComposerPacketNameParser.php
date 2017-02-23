<?php namespace Rancherize\Plugin\Composer;

use Rancherize\Plugin\Exceptions\ComposerNameParseFailure;

/**
 * Class ComposerPacketNameParser
 * @package Plugin\Installer
 */
class ComposerPacketNameParser {

	/**
	 * @param string $name
	 * @return ComposerPacket
	 */
	public function parse(string $name) {

		$composerPacket = new PODComposerPacket();

		$matches = [];
		preg_match('~([^/]+)/([^:]+):?(.*)~', $name, $matches);

		if( count($matches) < 2 )
			throw new ComposerNameParseFailure('Missing namespace');
		if( count($matches) < 3 )
			throw new ComposerNameParseFailure('Missing name');

		$composerPacket->setNamespace( $matches[1] );
		$composerPacket->setName( $matches[2] );

		if( array_key_exists(2, $matches) )
			$composerPacket->setVersion( $matches[3] );

		return $composerPacket;
	}

}