<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;

use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\MemoryLimit;

trait MemoryLimitTrait {

	/**
	 * @var string
	 */
	private $memoryLimit = MemoryLimit::DEFAULT_MEMORY_LIMIT;


	/**
	 * @return $this
	 */
	public function setMemoryLimit( $limit ) {
		$this->memoryLimit = $limit;
		return $this;
	}
}