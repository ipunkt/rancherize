<?php /** @noinspection PhpMissingBreakStatementInspection */

namespace Rancherize\Blueprint\ResourceLimit\EventListener;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;
use Rancherize\RancherAccess\RancherService;
use Rancherize\Services\UnitConversionService\UnitConversionService;

/**
 * Class ServiceWriteListener
 * @package Rancherize\Blueprint\ResourceLimit\EventListener
 */
class ServiceWriteListener {
	/**
	 * @var RancherService
	 */
	private $rancherService;
    /**
     * @var UnitConversionService
     */
    private $unitConversionService;

    /**
     * ServiceWriteListener constructor.
     * @param RancherService $rancherService
     * @param UnitConversionService $unitConversionService
     */

	public function __construct( RancherService $rancherService, UnitConversionService $unitConversionService ) {
		$this->rancherService = $rancherService;
        $this->unitConversionService = $unitConversionService;
    }

	/**
	 * @param ServiceWriterServicePreparedEvent $event
	 */
	public function writeService( ServiceWriterServicePreparedEvent $event ) {
		$dockerData = $event->getDockerContent();
		$rancherData = $event->getRancherContent();

		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation( ResourceLimitExtraInformation::IDENTIFIER );
		} catch (ExtraInformationNotFoundException $e) {
			return;
		}

		if ( !$extraInformation instanceof ResourceLimitExtraInformation )
			return;

		$this->rancherService->setCliMode( true );

		$rancherData = $this->addCpuReservation( $rancherData, $extraInformation );
		$dockerData = $this->addCpuLimit( $dockerData, $extraInformation );
		$dockerData = $this->addMemoryLimit( $dockerData, $extraInformation );
		$dockerData = $this->addMemoryReservation( $dockerData, $extraInformation );

		$event->setDockerContent( $dockerData );
		$event->setRancherContent( $rancherData );
	}

	/**
	 * @param $dockerData
	 * @param ResourceLimitExtraInformation $extraInformation
	 * @return mixed
	 */
	private function addCpuLimit( $dockerData, ResourceLimitExtraInformation $extraInformation ) {

		if ( $extraInformation->getCpuPeriod() === null || $extraInformation->getCpuQuota() === null )
			return $dockerData;

		$dockerData[ 'cpu_period' ] = $extraInformation->getCpuPeriod();
		$dockerData[ 'cpu_quota' ] = $extraInformation->getCpuQuota();

		return $dockerData;
	}

	/**
	 * @param $dockerData
	 * @param ResourceLImitExtraInformation $extraInformation
	 * @return mixed
	 */
	private function addMemoryLimit( $dockerData, ResourceLimitExtraInformation $extraInformation ) {

		if ( $extraInformation->getCpuPeriod() === null || $extraInformation->getCpuQuota() === null )
			return $dockerData;

		$memory = $extraInformation->getMemoryLimit();
		$dockerData[ 'mem_limit' ] = $this->unitConversionService->convert($memory);

		return $dockerData;
	}

	/**
	 * @param array $rancherData
	 * @param $extraInformation
	 * @return array
	 */
	private function addCpuReservation( array $rancherData, ResourceLimitExtraInformation $extraInformation ) {

		if ( $extraInformation->getCpuReservation() === null )
			return $rancherData;

		$rancherData[ 'milli_cpu_reservation' ] = $extraInformation->getCpuReservation();

		return $rancherData;
	}

	private function addMemoryReservation( $dockerData, ResourceLimitExtraInformation $extraInformation ) {

		if ( $extraInformation->getMemoryReservation() === null )
			return $dockerData;

		$memory = $extraInformation->getMemoryReservation();
		$dockerData[ 'mem_reservation' ] = $this->unitConversionService->convert($memory);

		return $dockerData;
	}
}