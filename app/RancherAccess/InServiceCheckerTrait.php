<?php


namespace Rancherize\RancherAccess;


trait InServiceCheckerTrait {

	/**
	 * @var InServiceChecker
	 */
	protected $inServiceChecker;

	/**
	 * @param InServiceChecker $inServiceChecker
	 */
	public function setInServiceChecker(InServiceChecker $inServiceChecker) {
		$this->inServiceChecker = $inServiceChecker;
	}

}