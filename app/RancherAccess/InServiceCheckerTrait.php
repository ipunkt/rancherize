<?php


namespace Rancherize\RancherAccess;


trait InServiceCheckerTrait {

	/**
	 * @var InServiceChecker
	 */
	protected $inServiceChecker;

	/**
	 * @return \Pimple\Container|InServiceChecker
	 */
	public function getInServiceChecker() {
		if( $this->inServiceChecker === null )
			$this->inServiceChecker = container('in-service-checker');
		return $this->inServiceChecker;
	}

	/**
	 * @param InServiceChecker $inServiceChecker
	 */
	public function setInServiceChecker(InServiceChecker $inServiceChecker) {
		$this->inServiceChecker = $inServiceChecker;
	}

}