<?php namespace Rancherize\Blueprint\PhpCommands;

use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Configuration;

/**
 * Class PhpCommand
 * @package Rancherize\Blueprint\PhpCommands
 */
class PhpCommand {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $command = '';

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * PhpCommand constructor.
	 * @param string $name
	 * @param string $command
	 */
	public function __construct( $name = '', $command = '') {
		$this->name = $name;
		$this->command = $command;
		$this->configuration = new ArrayConfiguration( [] );
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getCommand(): string {
		return $this->command;
	}

	/**
	 * @param string $command
	 */
	public function setCommand( string $command ) {
		$this->command = $command;
	}

	/**
	 * @param Configuration $configuration
	 */
	public function setConfiguration( Configuration $configuration ) {
		$this->configuration = $configuration;
	}

	/**
	 * @return Configuration
	 */
	public function getConfiguration(): Configuration {
		return $this->configuration;
	}

}