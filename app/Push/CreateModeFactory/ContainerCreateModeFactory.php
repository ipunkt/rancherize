<?php namespace Rancherize\Push\CreateModeFactory;

use Pimple\Container;
use Rancherize\Configuration\Configuration;
use Rancherize\Push\CreateMode\CreateMode;

/**
 * Class ContainerCreateModeFactory
 * @package Rancherize\Push\CreateModeFactory
 */
class ContainerCreateModeFactory implements CreateModeFactory  {

	protected $prefix = 'push.create.mode.';

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var string
	 */
	private $defaultMode;
	/**
	 * @var CreateModeParser
	 */
	private $createModeParser;

	/**
	 * ContainerCreateModeFactory constructor.
	 * @param Container $container
	 * @param CreateModeParser $createModeParser
	 */
	public function __construct( Container $container, CreateModeParser $createModeParser ) {
		$this->container = $container;
		$this->createModeParser = $createModeParser;
	}

	/**
	 * @param Configuration $configuration
	 * @return mixed
	 */
	public function make( Configuration $configuration ) {
		$mode = $this->createModeParser->getCreateMode($configuration, $this->defaultMode);

		return $this->container[$mode];
	}

	/**
	 * Register a createMode.
	 * If no Mode is registered yet it will be used as the default mode
	 *
	 * @param $modeName
	 * @param CreateMode $createMode
	 */
	public function register( $modeName, CreateMode $createMode ) {
		if( empty($this->defaultMode) )
			$this->defaultMode = $modeName;

		$this->container[$modeName] = function() use ($createMode) {
			return $createMode;
		};
	}

	/**
	 * @param string $defaultMode
	 */
	public function setDefaultMode( string $defaultMode ) {
		$this->defaultMode = $defaultMode;
	}
}