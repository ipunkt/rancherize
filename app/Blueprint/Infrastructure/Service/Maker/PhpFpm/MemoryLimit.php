<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

/**
 * Interface MemoryLimit
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface MemoryLimit {

	const DEFAULT_MEMORY_LIMIT = 'default';

	/**
	 * @return $this
	 */
	function setMemoryLimit($limit);

}