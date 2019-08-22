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
	 * @var string
	 */
	protected $restart = 'start-once';

	/**
	 * @var bool
	 */
	protected $service = false;

    /**
     * @var bool
     */
	protected $keepaliveService = false;

	/**
	 * @var Configuration
	 */
	protected $configuration;

    /**
     * @var bool
     */
	protected $networkShared = false;

	/**
	 * PhpCommand constructor.
	 * @param string $name
	 * @param string $command
	 * @param $service
	 */
	public function __construct( $name = '', $command = '', $service = false) {
		$this->name = $name;
		$this->command = $command;
		$this->configuration = new ArrayConfiguration( [] );
		$this->service = $service;
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

	/**
	 * @return string
	 */
	public function getRestart(): string {
		return $this->restart;
	}

	/**
	 * @param string $restart
	 */
	public function setRestart( string $restart ) {
		$this->restart = $restart;
	}

	/**
	 * @return bool
	 */
	public function isService(): bool {
		return $this->service;
	}

    /**
     * @param bool $keepaliveService
     * @return PhpCommand
     */
    public function setKeepaliveService(bool $keepaliveService): PhpCommand
    {
        $this->keepaliveService = $keepaliveService;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasKeepaliveService(): bool
    {
        return $this->keepaliveService;
    }

    /**
     * @param bool $networkShared
     * @return PhpCommand
     */
    public function setNetworkShared(bool $networkShared): PhpCommand
    {
        $this->networkShared = $networkShared;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNetworkShared(): bool
    {
        return $this->networkShared;
    }

}