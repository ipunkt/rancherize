<?php namespace Rancherize\Blueprint\ResourceLimit\Parser;

use Pimple\Container;
use Pimple\Exception\UnknownIdentifierException;
use Rancherize\Blueprint\ResourceLimit\Exceptions\InvalidMemoryLimitException;

/**
 * Class MemLimitModeFactory
 * @package Rancherize\Blueprint\ResourceLimit\Parser
 */
class MemLimitModeFactory {

	/**
	 * @var Container
	 */
	private $container;

	protected $prefix = 'resource-limit.mem-limit.';

	/**
	 * CpuLimitModeFactory constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * @param string $identifier
	 * @return MemLimitMode
	 */
	public function make(string $identifier) {
		try {
			return $this->container[$this->prefix.$identifier];
		} catch(UnknownIdentifierException $e) {
			throw new InvalidMemoryLimitException('Invalid memory limit '.$identifier);
		}
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix( string $prefix ) {
		$this->prefix = $prefix;
	}
}