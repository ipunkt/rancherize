<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


trait DebugImageTrait {

	/**
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * @param $debug
	 */
	public function setDebug($debug) {
		$this->debug = $debug;
	}

	public function isDebug(  ) {
		return $this->debug;
	}
}