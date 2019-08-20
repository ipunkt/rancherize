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
	public function parse( string $name, $data, $version ) {
		$commandName = $name;
		if ( array_key_exists( 'name', $data ) )
			$commandName = $data['name'];

		if ( !array_key_exists( 'command', $data ) )
			throw new NoCommandException( 'No command set for php-command ' . $name );

		$isService = false;
		if ($version >= 3)
			$isService = true;

		if(array_key_exists('is-service', $data) && is_bool($data['is-service']) )
			$isService = $data['is-service'];

		$command = $data['command'];

		$phpCommand = new PhpCommand( $commandName, $command, $isService );

		if ( array_key_exists( 'restart', $data ) )
			$phpCommand->setRestart( $data['restart'] );

        if ( array_key_exists( 'keepalive', $data ) ) {
            $phpCommand->setKeepaliveService( $data['keepalive'] );
        }

		return $phpCommand;
	}
}