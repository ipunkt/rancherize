<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;

/**
 * Class AlpineDebugImageBuilder
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
class AlpineDebugImageBuilder {

	/**
	 * @param $baseImage
	 * @param string $xdebugVersion Will be used as xdebug version for pecl install: pecl install xdebug-$xdebugVersion
	 * @return Dockerfile
	 */
	public function makeImage($baseImage, $xdebugVersion = null) {
		if($xdebugVersion !== null)
			$xdebugVersion = '-'.$xdebugVersion;

		$debugDockerfile = new Dockerfile();
		$debugDockerfile->setFrom( $baseImage );
		$debugDockerfile->run('apk add --no-cache $PHPIZE_DEPS');
		$debugDockerfile->run('docker-php-source extract');
		$debugDockerfile->run('pecl install xdebug'.$xdebugVersion);
		$debugDockerfile->run('docker-php-source delete');
		$debugDockerfile->run('docker-php-ext-enable xdebug');
		$debugDockerfile->run('apk del $PHPIZE_DEPS');

		return $debugDockerfile;
	}
}