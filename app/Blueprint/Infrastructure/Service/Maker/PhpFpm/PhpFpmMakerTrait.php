<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;


trait PhpFpmMakerTrait {

	/**
	 * @var PhpFpmMaker
	 */
	protected $phpMaker = null;

	/**
	 * @return PhpFpmMaker
	 */
	public function getPhpFpmMaker() {
		if($this->phpMaker === null)
			$this->phpMaker = container('php-fpm-maker');

		return $this->phpMaker;
	}
}