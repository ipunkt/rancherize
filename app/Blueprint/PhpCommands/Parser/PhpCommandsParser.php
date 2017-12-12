<?php namespace Rancherize\Blueprint\PhpCommands\Parser;

use Rancherize\Blueprint\PhpCommands\PhpCommand;
use Rancherize\Configuration\Configuration;

/**
 * Class PhpCommandsParser
 * @package Rancherize\Blueprint\PhpCommands\Parser
 */
class PhpCommandsParser {

	/**
	 * @param Configuration $configuration
	 * @return PhpCommand[]
	 */
	public function parse( Configuration $configuration ) {
		$commands = [];

		if( !$configuration->has('php-commands') )
			return $this->defaults();

		return $commands;
	}

	/**
	 * @return PhpCommand[]
	 */
	private function defaults() {
		return [
			new PhpCommand('migrate-seed', "sh -c 'php /var/www/app/artisan migrate && php /var/www/app/artisan db:seed'"),
		];
	}
}