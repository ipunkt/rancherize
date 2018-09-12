<?php namespace Rancherize\Blueprint\PhpCommands\Parser;

use Rancherize\Blueprint\PhpCommands\PhpCommand;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class PhpCommandsParser
 * @package Rancherize\Blueprint\PhpCommands\Parser
 */
class PhpCommandsParser {
	/**
	 * @var ArrayParser
	 */
	private $arrayParser;
	/**
	 * @var NameParser
	 */
	private $nameParser;

	/**
	 * PhpCommandsParser constructor.
	 * @param ArrayParser $arrayParser
	 * @param NameParser $nameParser
	 */
	public function __construct( ArrayParser $arrayParser, NameParser $nameParser ) {
		$this->arrayParser = $arrayParser;
		$this->nameParser = $nameParser;
	}

	/**
	 * @param Configuration $configuration
	 * @return PhpCommand[]
	 */
	public function parse( Configuration $configuration ) {
		$commands = [];

		if( !$configuration->has('php-commands') )
			return $this->defaults();

		foreach($configuration->get('php-commands') as $name => $command) {
			if ( is_array( $command ) )
				$phpCommand = $this->arrayParser->parse( $name, $command, $configuration->version() );
			else
				$phpCommand = $this->nameParser->parse( $name, $command, $configuration->version() );

			$commandConfig = new PrefixConfigurationDecorator( $configuration, 'php-commands.' . $name . '.' );
			$phpCommand->setConfiguration( $commandConfig );

			$commands[] = $phpCommand;
		}

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