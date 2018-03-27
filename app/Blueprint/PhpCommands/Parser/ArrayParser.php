<?php namespace Rancherize\Blueprint\PhpCommands\Parser;

use Rancherize\Blueprint\PhpCommands\Exceptions\NoCommandException;
use Rancherize\Blueprint\PhpCommands\PhpCommand;

/**
 * Class ArrayParser
 * @package Rancherize\Blueprint\PhpCommands\Parser
 */
class ArrayParser implements PhpCommandParser {

	/**
	 * @param string $name
	 * @param $data
	 * @return PhpCommand
	 */
	public function parse( string $name, $data ) {
		$commandName = $name;
		if ( array_key_exists( 'name', $data ) )
			$commandName = $data['name'];

		if ( !array_key_exists( 'command', $data ) )
			throw new NoCommandException( 'No command set for php-command ' . $name );


		$command = $data['command'];

		$phpCommand = new PhpCommand( $commandName, $command );

		if ( array_key_exists( 'restart', $data ) )
			$phpCommand->setRestart( $data['restart'] );

		return $phpCommand;
	}
}