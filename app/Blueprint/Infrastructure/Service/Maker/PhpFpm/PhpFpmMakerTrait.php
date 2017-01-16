<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;


use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmMaker;

trait PhpFpmMakerTrait {

	/**
	 * @return PhpFpmMaker
	 */
	public function getPhpFpmMaker() {
		return container('php-fpm-maker');
	}
}