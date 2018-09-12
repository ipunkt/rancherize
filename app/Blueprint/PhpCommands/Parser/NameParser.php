<?php namespace Rancherize\Blueprint\PhpCommands\Parser;

use Rancherize\Blueprint\PhpCommands\PhpCommand;

/**
 * Class NameParser
 * @package Rancherize\Blueprint\PhpCommands\Parser
 */
class NameParser implements PhpCommandParser {

	/**
	 * @param string $name
	 * @param $data
	 * @param $version
	 * @return PhpCommand
	 */
	public function parse( string $name, $data, $version ) {

		$isService = false;
		if ($version >= 4)
			$isService = true;


		return new PhpCommand( $name, $data, $isService );
	}
}