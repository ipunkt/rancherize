<?php /** @noinspection PhpMissingBreakStatementInspection */

namespace Rancherize\Blueprint\ResourceLimit\EventListener;

use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\ExtraInformationNotFoundException;
use Rancherize\Blueprint\ResourceLimit\ExtraInformation\ExtraInformation as ResourceLimitExtraInformation;

/**
 * Class ServiceWriteListener
 * @package Rancherize\Blueprint\ResourceLimit\EventListener
 */
class ServiceWriteListener {

	/**
	 * @param ServiceWriterServicePreparedEvent $event
	 */
	public function writeService( ServiceWriterServicePreparedEvent $event ) {
		$dockerData = $event->getDockerContent();
		$rancherData = $event->getRancherContent();

		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation( ResourceLimitExtraInformation::IDENTIFIER );
		} catch ( ExtraInformationNotFoundException $e ) {
			return;
		}

		if ( !$extraInformation instanceof ResourceLimitExtraInformation )
			return;

		$rancherData = $this->addCpuReservation( $rancherData, $extraInformation );
		$dockerData = $this->addCpuLImit( $dockerData, $extraInformation );
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
	private function addCpuLImit( $dockerData, ResourceLimitExtraInformation $extraInformation ) {

		if ( $extraInformation->getCpuPeriod() === null || $extraInformation->getCpuQuota() === null )
			return $dockerData;

		$dockerData['cpu_period'] = $extraInformation->getCpuPeriod();
		$dockerData['cpu_quota'] = $extraInformation->getCpuQuota();

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
		preg_match( '~(\d+)([gGmM]?)~', $memory, $matches );
		$memory = (int)$matches[1];
		$modifier = $matches[2];
		switch ( $modifier ) {
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
		$dockerData['mem_limit'] = $memory;

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

		$memory = $extraInformation->getCpuReservation();
		preg_match( '~(\d+)([gGmM]?)~', $memory, $matches );
		$memory = (int)$matches[1];
		$modifier = $matches[2];
		switch ( $modifier ) {
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
		$rancherData['milli_cpu_reservation'] = $memory;

		return $rancherData;
	}

	private function addMemoryReservation( $dockerData, ResourceLimitExtraInformation $extraInformation ) {

		if( $extraInformation->getMemoryReservation() === null)
			return $dockerData;

		$dockerData['mem_reservation'] = $extraInformation->getMemoryReservation();

		return $dockerData;
	}
}