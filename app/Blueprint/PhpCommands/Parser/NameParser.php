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
	 * @return PhpCommand
	 */
	public function parse( string $name, $data ) {
		return new PhpCommand( $name, $data );
	}
}