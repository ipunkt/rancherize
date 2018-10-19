<?php /** @noinspection PhpMissingBreakStatementInspection */

namespace Rancherize\Blueprint\ResourceLimit\Parser;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\ResourceLimit\Exceptions\ZeroMemoryLimitException;
use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class Parser
 * @package Rancherize\Blueprint\ResourceLimit\Parser
 */
class Parser {
	/**
	 * @var CpuLimitModeFactory
	 */
	private $modeFactory;
	/**
	 * @var MemLimitModeFactory
	 */
	private $memModeFactory;

	/**
	 * Parser constructor.
	 * @param CpuLimitModeFactory $modeFactory
	 * @param MemLimitModeFactory $memModeFactory
	 */
	public function __construct( CpuLimitModeFactory $modeFactory, MemLimitModeFactory $memModeFactory ) {
		$this->modeFactory = $modeFactory;
		$this->memModeFactory = $memModeFactory;
	}

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 */
	public function parse( Service $service, Configuration $configuration ) {

		$resourceLimitConfig = new PrefixConfigurationDecorator( $configuration, 'resource-limit.' );

		if ( !$resourceLimitConfig->get( 'enable', true ) )
			return;

		$information = new ResourceLimitExtraInformation();
		$this->cpuReservations( $information, $resourceLimitConfig );
		$this->memoryReservations( $information, $resourceLimitConfig );

		$this->parseLimit( $service, $configuration, $information );

		$service->addExtraInformation( $information );


	}

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 * @param ResourceLimitExtraInformation|null $information
	 */
	public function parseLimit( Service $service, Configuration $configuration, ResourceLimitExtraInformation $information = null ) {

		if ( $information === null )
			$information = new ResourceLimitExtraInformation();

		$resourceLimitConfig = new PrefixConfigurationDecorator( $configuration, 'resource-limit.' );

		if ( !$resourceLimitConfig->get( 'enable', true ) )
			return;

		$this->cpuLimits( $information, $resourceLimitConfig );
		$this->memoryLimits( $information, $resourceLimitConfig );

		$service->addExtraInformation( $information );

	}

	/**
	 * @param ResourceLimitExtraInformation $information
	 * @param Configuration $configuration
	 */
	private function cpuLimits( ResourceLimitExtraInformation $information, Configuration $configuration ) {

		if ( !$configuration->has( 'cpu' ) )
			return;

		$mode = $this->modeFactory->make( $configuration->get( 'cpu' ) );
		$mode->setLimit( $information );

	}

	/**
	 * @param ResourceLimitExtraInformation $information
	 * @param Configuration $configuration
	 */
	private function memoryLimits( ResourceLimitExtraInformation $information, Configuration $configuration ) {
		if( $configuration->has('mem') ) {

			$memMode = $this->memModeFactory->make( $configuration->get( 'mem' ) );
			$memMode->setLimit( $information );

			return;
		}

		if ( !$configuration->has( 'memory' ) )
			return;

		$memory = $configuration->get( 'memory' );
        /**
         * Removed unit version to bytes as this is now done in the ServiceWriter
         */
		if ( $memory === 0 )
			throw new ZeroMemoryLimitException( 'Attempting to set the memory limit to zero' );

		$information->setMemoryLimit( $memory );
        $information->setMemoryReservation( $memory );
	}

	/**
	 * @param ResourceLimitExtraInformation $information
	 * @param PrefixConfigurationDecorator $resourceLimitConfig
	 */
	private function cpuReservations( ResourceLimitExtraInformation $information, PrefixConfigurationDecorator $configuration ) {
		if ( !$configuration->has( 'cpu' ) )
			return;

		$mode = $this->modeFactory->make( $configuration->get( 'cpu' ) );
		$mode->setReservation( $information );
	}

	/**
	 * @param ResourceLimitExtraInformation $information
	 * @param PrefixConfigurationDecorator $resourceLimitConfig
	 */
	private function memoryReservations( ResourceLimitExtraInformation $information, PrefixConfigurationDecorator $configuration ) {
		if ( !$configuration->has( 'mem' ) )
			return;

		$memMode = $this->memModeFactory->make( $configuration->get( 'mem' ) );
		$memMode->setReservation( $information );
	}

}