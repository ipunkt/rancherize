<?php namespace Rancherize\Blueprint\PhpCommands\Parser;

use Rancherize\Blueprint\PhpCommands\PhpCommand;


/**
 * Interface PhpCommandParser
 * @package Rancherize\Blueprint\PhpCommands\Parser
 */
interface PhpCommandParser {

	/**
	 * @param string $name
	 * @param $data
	 * @return PhpCommand
	 */
	function parse( string $name, $data );

}