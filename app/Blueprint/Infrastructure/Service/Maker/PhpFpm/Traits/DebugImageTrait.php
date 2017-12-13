<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


trait DebugImageTrait {

	/**
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * For xdebug this is the remote_host address
	 *
	 * @var string
	 */
	protected $debugListener;

	/**
	 * @param $debug
	 */
	public function setDebug($debug) {
		$this->debug = $debug;
	}

	public function isDebug(  ) {
		return $this->debug;
	}

	/**
	 * @return string
	 */
	public function getDebugListener(): string {
		return $this->debugListener;
	}

	/**
	 * @param string $debugListener
	 */
	public function setDebugListener( $debugListener ) {
		$this->debugListener = $debugListener;
	}
}