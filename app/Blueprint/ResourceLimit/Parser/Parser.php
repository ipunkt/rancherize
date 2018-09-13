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
	 * Parser constructor.
	 * @param CpuLimitModeFactory $modeFactory
	 */
	public function __construct( CpuLimitModeFactory $modeFactory ) {
		$this->modeFactory = $modeFactory;
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
		if ( !$configuration->has( 'memory' ) )
			return;

		$memory = $configuration->get( 'memory' );
		preg_match('~(\d+)([gGmM]?)~', $memory, $matches);
		$memory = (int)$matches[0][0];
		$modifier = $matches[1][0];
		switch($modifier) {
			case 'g':
			case 'G':
				$memory *= 1024;

			case 'm':
			case 'M':
				$memory *= 1024;

			case 'k':
			case 'K':
				$memory *= 1024;

			default:
				break;
		}

		if ( $memory === 0 )
			throw new ZeroMemoryLimitException( 'Attempting to set the memory limit to zero' );

		$information->setMemoryLimit( $memory );
	}

}