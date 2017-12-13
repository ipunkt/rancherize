<?php namespace Rancherize\Blueprint\PhpCommands;

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
	 * PhpCommand constructor.
	 * @param string $name
	 * @param string $command
	 */
	public function __construct( $name = '', $command = '') {
		$this->name = $name;
		$this->command = $command;
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

}