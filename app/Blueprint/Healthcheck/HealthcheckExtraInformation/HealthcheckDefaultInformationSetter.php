<?php namespace Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation;

/**
 * Class HealthcheckDefaultInformationSetter
 * @package Rancherize\Blueprint\Healthcheck\HealthcheckExtraInformation
 */
class HealthcheckDefaultInformationSetter {

	/**
	 * @param HealthcheckExtraInformation $extraInformation
	 */
	public function setDefaults( HealthcheckExtraInformation $extraInformation ) {

		$extraInformation->setPort(80);

		$extraInformation->setHealthyThreshold( 2 );
		$extraInformation->setUnhealthyThreshold( 3 );

		$extraInformation->setResponseTimeout( 2000 );
		$extraInformation->setInterval( 2000 );
		$extraInformation->setInitializingTimeout( 60000 );
		$extraInformation->setReinitializingTimeout( 60000 );

		$extraInformation->setStrategy( HealthcheckExtraInformation::STRATEGY_NONE );

	}

}