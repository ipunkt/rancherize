<?php namespace Rancherize\Blueprint\ResourceLimit\Parser;

use Pimple\Container;
use Pimple\Exception\UnknownIdentifierException;
use Rancherize\Blueprint\ResourceLimit\Exceptions\InvalidCpuLimitException;

/**
 * Class CpuLimitModeFactory
 * @package Rancherize\Blueprint\ResourceLimit\Parser
 */
class CpuLimitModeFactory {
	/**
	 * @var Container
	 */
	private $container;

	protected $prefix = 'resource-limit.cpu-limit.';

	/**
	 * CpuLimitModeFactory constructor.
	 * @param Container $container
	 */
	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * @param string $identifier
	 * @return CpuLimitMode
	 */
	public function make(string $identifier) {
		try {
			return $this->container[$this->prefix.$identifier];
		} catch(UnknownIdentifierException $e) {
			throw new InvalidCpuLimitException('Invalid cpu limit '.$identifier);
		}
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix( string $prefix ) {
		$this->prefix = $prefix;
	}

}