<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

/**
 * Interface DebugImage
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface DebugImage {

	/**
	 * @param $debug
	 */
	function setDebug($debug);

	/**
	 * @param $listener
	 */
	function setDebugListener( $listener);

}