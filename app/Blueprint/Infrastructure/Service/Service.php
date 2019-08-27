<?php namespace Rancherize\Blueprint\Infrastructure\Service;

use Closure;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Service\NetworkMode\DefaultNetworkMode;
use Rancherize\Blueprint\Infrastructure\Service\NetworkMode\NetworkMode;

/**
 * Class Service
 * @package Rancherize\Blueprint\Infrastructure
 *
 * Data object representing a service to be written to disk later
 */
class Service {
	/**
	 * Either the name as a string or a closure taking $this as parameter.
	 * @see setName
	 *
	 * @var string|Closure
	 */
	protected $name = '';

	const ALWAYS_PULLED_TRUE = true;
	const ALWAYS_PULLED_DEFAULT = 'default';
	const ALWAYS_PULLED_FALSE = false;

	/**
	 * Always pull Image on upgrades?
	 *
	 * @var bool
	 */
	protected $alwaysPulled = self::ALWAYS_PULLED_DEFAULT;

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

	/**
	 * @var string
	 */
	protected $workDir = '';

    /**
     * @var Service[]
     */
    protected $copyVolumesFrom = [];

    /**
     * @var Service
     */
    protected $mantleService;

	const RESTART_UNLESS_STOPPED = 0;
	const RESTART_NEVER = 1;
	/**
	 * @deprecated use RESTART_ALWAYS instead
	 */
	const RESTART_AWAYS = 2;
	const RESTART_ALWAYS = 2;
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
	 * @var NetworkMode
	 */
	protected $networkMode;

	/**
	 * @var Closure|null
	 */
	protected $environmentVariablesCallback = null;

	public function __construct() {
		$this->networkMode = new DefaultNetworkMode();
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		if($this->name instanceof Closure) {
			$callback = $this->name;

			return $callback($this);
		}

		return $this->name;
	}

	/**
	 * If a closure is set then the closure is called with $closure($this) every time the name is read with getName()
	 * Use it if the name is relative to some other container.
	 *
	 * Example:
	 * $service->setName( function($Service $myService) use ($mainService) { return $mainService->getName().'-PHP-FPM'; } );
	 *
	 * @param string|Closure $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string|Dockerfile
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param string|Dockerfile $image
	 */
	public function setImage($image) {
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
	    if($this->isMantled())
	        return [];

		return $this->exposedPorts;
	}

	/**
	 * @param int $internalPort
	 * @param int $externalPort
	 */
	public function expose(int $internalPort, int $externalPort) {
	    if($this->isMantled()) {
	        $this->mantleService->expose($internalPort, $externalPort);
	        return;
        }

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
		if($this->environmentVariablesCallback instanceof Closure ) {
			$callback = $this->environmentVariablesCallback;

			return array_merge($this->environmentVariables, $callback($this));
		}
		return $this->environmentVariables;
	}

	/**
	 * Set a callback which will be called by getEnvironmentVariable.
	 * The callback receives this Service as parameter and must return an array ['environment_variable' => 'value'].
	 * The returned values are merged with the environment variables set with `setEnvironmentVariable` with the ones from
	 * the callback winning if both set the same value
	 *
	 * @param Closure $closure
	 */
	public function setEnvironmentVariablesCallback( Closure $closure = null ) {
		$this->environmentVariablesCallback = $closure;
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
	    if($this->isMantled())
	        return [];

		return $this->links;
	}

	/**
	 * @param Service $service
	 * @param string|null $name
	 */
	public function addLink(Service $service, $name = null) {
		if($service === $this)
			return;

        if($this->isMantled()) {
            $this->mantleService->addLink($service, $name);
            return;
        }

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
        if($this->isMantled()) {
            $this->mantleService->addLinksFrom($service);
            return;
        }

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
        if($this->isMantled()) {
            return [];
        }

		return $this->externalLinks;
	}

	/**
	 * @param string $externalLink
	 * @param string $name
	 */
	public function addExternalLink(string $externalLink, string $name = null) {
        if($this->isMantled()) {
            $this->mantleService->addExternalLink($externalLink, $name);
            return;
        }

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

    public function copyLabels(Service $copyFrom)
    {
        $this->labels = $copyFrom->labels;
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
        if($this->isMantled()) {
            return [];
        }

		return $this->sidekicks;
	}

    public function copySidekicks(Service $copyFrom)
    {
        if($this->isMantled()) {
            $this->mantleService->copySidekicks($copyFrom);
            return;
        }

        $this->sidekicks = $copyFrom->sidekicks;
	}

	/**
	 * @param Service $sidekicks
	 */
	public function addSidekick(Service $sidekicks) {
        if($this->isMantled()) {
            $this->mantleService->addSidekick($sidekicks);
            return;
        }

		$this->sidekicks[] = $sidekicks;
	}

    public function resetSidekicks()
    {
        $this->sidekicks = [];
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

	/**
	 * @return string
	 */
	public function getWorkDir(): string {
		return $this->workDir;
	}

	/**
	 * @param string $workDir
	 */
	public function setWorkDir( string $workDir ) {
		$this->workDir = $workDir;
	}

    /**
     * @return DefaultNetworkMode|NetworkMode
     */
    public function getNetworkModeObject()
    {
        return $this->networkMode;
	}

	/**
	 * @return string
	 */
	public function getNetworkMode(): string {
		return $this->networkMode->getNetworkMode();
	}

	/**
	 * @param NetworkMode $networkMode
	 */
	public function setNetworkMode( NetworkMode $networkMode ) {
		$this->networkMode = $networkMode;
	}

	/**
	 * @return bool
	 */
	public function isAlwaysPulled() {
		return $this->alwaysPulled;
	}

	/**
	 * @param bool $alwaysPulled
	 */
	public function setAlwaysPulled( $alwaysPulled ) {
		$this->alwaysPulled = $alwaysPulled;
	}

    /**
     * @return Service[]
     */
    public function getCopyVolumesFrom(): array
    {
        if($this->mantleService instanceof Service) {
        }

        return $this->copyVolumesFrom;
    }

    /**
     * @param Service $service
     */
    public function addCopyVolumesFrom(Service $service)
    {
        $this->copyVolumesFrom[] = $service;
    }

    /**
     * @param Service $mantleService
     * @return Service
     */
    public function setMantleService(Service $mantleService): Service
    {
        $this->mantleService = $mantleService;
        return $this;
    }

    protected function isMantled() {
        return $this->mantleService instanceof Service;
    }
}