<?php namespace Rancherize\Blueprint\Infrastructure\Service;

/**
 * Class Service
 * @package Rancherize\Blueprint\Infrastructure
 *
 * Data object representing a service to be written to disk later
 */
class Service {
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var int
	 */
	protected $scale = 1;

	/**
	 * @var string
	 */
	protected $image = '';

	/**
	 * @var Volume[]
	 */
	protected $volumes = [];

	/**
	 * @var Service[]
	 */
	protected $volumesFrom = [];

	/**
	 * @var int[]
	 */
	protected $exposedPorts = [];

	/**
	 * @var bool
	 */
	protected $keepStdin = false;

	/**
	 * @var Service[]
	 */
	protected $sidekicks = [];

	/**
	 * @var string[]
	 */
	protected $labels = [];

	/**
	 * extra information set and retrieved by plugins
	 *
	 * @var ServiceExtraInformation[]
	 */
	protected $extraInformation = [];

	const RESTART_UNLESS_STOPPED = 0;
	const RESTART_NEVER = 1;
	const RESTART_AWAYS = 2;
	const RESTART_START_ONCE = 3;

	/**
	 * @var string
	 */
	protected $command = '';

	/**
	 * @var int
	 */
	protected $restart = self::RESTART_UNLESS_STOPPED;

	/**
	 * @var bool
	 */
	protected $tty = false;

	/**
	 * @var string[]
	 */
	protected $environmentVariables = [];

	/**
	 * @var Service[]
	 */
	protected $links = [];

	/**
	 * @var string[]
	 */
	protected $externalLinks = [];

	/**
	 * If true: use upgrade strategy start before stopping.
	 *
	 * @var bool
	 */
	protected $startFirst = true;

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getImage(): string {
		return $this->image;
	}

	/**
	 * @param string $image
	 */
	public function setImage(string $image) {
		$this->image = $image;
	}

	/**
	 * @return \string[]
	 */
	public function getVolumes(): array {

		$volumes = [];

		/**
		 *
		 */
		foreach($this->volumes as $volume) {
			$volumes[$volume->getExternalPath()] = $volume->getInternalPath();
		}

		return $volumes;
	}

	/**
	 * @return Volume[]
	 */
	public function getVolumeObjects( ) {
		return $this->volumes;
	}

	/**
	 * @param $nameOrVolume
	 * @param $internalPath
	 */
	public function addVolume( $nameOrVolume, $internalPath = null) {
		if($nameOrVolume instanceof Volume) {
			$this->volumes[ $nameOrVolume->getExternalPath() ] = $nameOrVolume;
			return;
		}

		$volume = new Volume();
		$volume->setExternalPath($nameOrVolume);
		$volume->setInternalPath($internalPath);

		$this->volumes[$nameOrVolume] = $volume;
	}

	/**
	 * @return Service[]
	 */
	public function getVolumesFrom(): array {
		return $this->volumesFrom;
	}

	/**
	 * @param Service $service
	 */
	public function addVolumeFrom(Service $service) {
		$this->volumesFrom[] = $service;
	}

	/**
	 * @return \int[]
	 */
	public function getExposedPorts(): array {
		return $this->exposedPorts;
	}

	/**
	 * @param int $internalPort
	 * @param int $externalPort
	 */
	public function expose(int $internalPort, int $externalPort) {
		$this->exposedPorts[$internalPort] = $externalPort;
	}

	/**
	 * @return int
	 */
	public function getRestart(): int {
		return $this->restart;
	}

	/**
	 * @param int $restart
	 */
	public function setRestart(int $restart) {
		$this->restart = $restart;
	}

	/**
	 * @return boolean
	 */
	public function isTty(): bool {
		return $this->tty;
	}

	/**
	 * @param boolean $tty
	 */
	public function setTty(bool $tty) {
		$this->tty = $tty;
	}

	/**
	 * @return \string[]
	 */
	public function getEnvironmentVariables(): array {
		return $this->environmentVariables;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setEnvironmentVariable(string $name, string $value) {
		$this->environmentVariables[$name] = $value;
	}

    /**
     * @param Service $service
     */
	public function setEnvironmentVariablesFrom(Service $service) {
	    $environmentVariables = $service->getEnvironmentVariables();
		foreach($environmentVariables as $name => $value) {
		    $this->setEnvironmentVariable($name, $value);
        }
	}

	/**
	 * @param string $name
	 * @param string $default
	 * @return string
	 */
	public function getEnvironmentVariable(string $name, string $default = null) {
		if( !array_key_exists($name, $this->environmentVariables) )
			return $default;

		return $this->environmentVariables[$name];
	}

	/**
	 * @return boolean
	 */
	public function isKeepStdin(): bool {
		return $this->keepStdin;
	}

	/**
	 * @param boolean $keepStdin
	 */
	public function setKeepStdin(bool $keepStdin) {
		$this->keepStdin = $keepStdin;
	}

	/**
	 * @return Service[]
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 * @param Service $service
	 * @param string|null $name
	 */
	public function addLink(Service $service, $name = null) {
		if($name === null) {
			$this->links[] = $service;
			return;
		}

		$this->links[$name] = $service;
	}

    /**
     * Add internal and external links from other service
     * @param Service $service
     */
	public function addLinksFrom(Service $service) {
	    $links = $service->getLinks();
	    foreach($links as $name => $link) {
	        if (is_numeric($name)) {
                $this->addLink($link);
            } else {
                $this->addLink($link, $name);
            }
        }

        $externalLinks = $service->getExternalLinks();
	    foreach($externalLinks as $name => $link) {
	        if (is_numeric($name)) {
                $this->addExternalLink($link);
            } else {
                $this->addExternalLink($link, $name);
            }
        }

	}

	/**
	 * @return \string[]
	 */
	public function getExternalLinks(): array {
		return $this->externalLinks;
	}

	/**
	 * @param string $externalLink
	 * @param string $name
	 */
	public function addExternalLink(string $externalLink, string $name = null) {
		if($name === null) {
			$this->externalLinks[] = $externalLink;
			return;
		}

		$this->externalLinks[$name] = $externalLink;
	}

	/**
	 * @return int
	 */
	public function getScale(): int {
		return $this->scale;
	}

	/**
	 * @param int $scale
	 */
	public function setScale(int $scale) {
		$this->scale = $scale;
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
	 * @return \string[]
	 */
	public function getLabels(): array {
		return $this->labels;
	}

	/**
	 * @param string $name
	 * @param string $label
	 */
	public function addLabel(string $name, string $label) {
		$this->labels[$name] = $label;
	}

	/**
	 * @return Service[]
	 */
	public function getSidekicks(): array {
		return $this->sidekicks;
	}

	/**
	 * @param Service $sidekicks
	 */
	public function addSidekick(Service $sidekicks) {
		$this->sidekicks[] = $sidekicks;
	}

	/**
	 * @return bool
	 */
	public function isStartFirst(): bool {
		return $this->startFirst;
	}

	/**
	 * @param bool $startFirst
	 */
	public function setStartFirst(bool $startFirst) {
		$this->startFirst = $startFirst;
	}

	/**
	 * @param $identifier
	 * @return ServiceExtraInformation
	 */
	public function getExtraInformation( $identifier ) {
		if( !array_key_exists($identifier, $this->extraInformation) )
			throw new ExtraInformationNotFoundException($identifier);

		return $this->extraInformation[ $identifier ];
	}

	/**
	 * @param ServiceExtraInformation $extraInformation
	 */
	public function addExtraInformation( ServiceExtraInformation $extraInformation ) {

		$identifier = $extraInformation->getIdentifier();

		$this->extraInformation[ $identifier ] = $extraInformation;
	}


}