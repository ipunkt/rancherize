<?php namespace Rancherize\Blueprint\ResourceLimit\EventListener;

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

		$service = $event->getService();
		try {
			$extraInformation = $service->getExtraInformation( ResourceLimitExtraInformation::IDENTIFIER );
		} catch ( ExtraInformationNotFoundException $e ) {
			return;
		}

		if ( !$extraInformation instanceof ResourceLimitExtraInformation )
			return;

		$dockerData = $this->addCpuLImit( $dockerData, $extraInformation );
		$dockerData = $this->addMemoryLimit( $dockerData, $extraInformation );

		$event->setDockerContent( $dockerData );
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

		$dockerData['mem_limit'] = (int)$extraInformation->getMemoryLimit();

		return $dockerData;
	}
}