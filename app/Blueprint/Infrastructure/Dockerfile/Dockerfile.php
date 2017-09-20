<?php namespace Rancherize\Blueprint\Infrastructure\Dockerfile;

/**
 * Class Dockerfile
 * @package Rancherize\Blueprint\Infrastructure
 *
 * Data object representing a dockerfile to be written to disk later
 */
class Dockerfile {

	/**
	 * @var string
	 */
	protected $from = '';

	/**
	 * @var string
	 */
	protected $command = '';

	/**
	 * @var string
	 */
	protected $workdir = '';

	/**
	 * @var string
	 */
	protected $entrypoint = '';

	/**
	 * @var string[]
	 */
	protected $volumes;

	/**
	 * @var string[]
	 */
	protected $copies = [];

	/**
	 * @var string[]
	 */
	protected $runCommands = [];

	/**
	 * @var string
	 */
	protected $user = '';

	/**
	 * @var string
	 */
	protected $group = '';

	/**
	 * @param string $from
	 */
	public function setFrom(string $from) {
		$this->from = $from;
	}

	/**
	 * @return string
	 */
	public function getFrom(): string {
		return $this->from;
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
	public function setCommand(string $command) {
		$this->command = $command;
	}

	/**
	 * @return string
	 */
	public function getWorkdir(): string {
		return $this->workdir;
	}

	/**
	 * @param string $workdir
	 */
	public function setWorkdir(string $workdir) {
		$this->workdir = $workdir;
	}

	/**
	 * @return string
	 */
	public function getEntrypoint(): string {
		return $this->entrypoint;
	}

	/**
	 * @param string $entrypoint
	 */
	public function setEntrypoint(string $entrypoint) {
		$this->entrypoint = $entrypoint;
	}

	/**
	 * @return \string[]
	 */
	public function getVolumes(): array {
		return $this->volumes;
	}

	/**
	 * @param string $volume
	 */
	public function addVolume(string $volume) {

		// [$volume] from having the same volume set multiple times
		$this->volumes[$volume] = $volume;
	}

	/**
	 * @return array
	 */
	public function getCopies(): array {
		return $this->copies;
	}

	/**
	 * @param string $from
	 * @param string $target
	 */
	public function copy(string $from, string $target) {
		$this->copies[$from] = $target;
	}

	/**
	 * @return \string[]
	 */
	public function getRunCommands(): array {
		return $this->runCommands;
	}

	/**
	 * @param $command
	 */
	public function run($command) {
		$this->runCommands[$command] = $command;
	}

	/**
	 * @return string
	 */
	public function getUser(): string {
		return $this->user;
	}

	/**
	 * @param string $user
	 */
	public function setUser( string $user ) {
		$this->user = $user;
	}

	/**
	 * @return string
	 */
	public function getGroup(): string {
		return $this->group;
	}

	/**
	 * @param string $group
	 */
	public function setGroup( string $group ) {
		$this->group = $group;
	}

}
