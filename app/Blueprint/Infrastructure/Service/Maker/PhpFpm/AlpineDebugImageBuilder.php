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
		$debugDockerfile->addInlineFile('/etc/confd/conf.d/xdebug.ini.toml',
'[template]
src = "xdebug.ini.tpl"
dest = "/usr/local/etc/php/conf.d/30-xdebug.ini"
');
		$debugDockerfile->addInlineFile('/etc/confd/templates/xdebug.ini.tpl',
'[xdebug]
xdebug.remote_autostart=On
xdebug.remote_enable=On
{{ if getenv "XDEBUG_REMOTE_HOST" }}
xdebug.remote_host={{ getenv "XDEBUG_REMOTE_HOST" }}
{{ else }}
xdebug.remote_connect_back=On
{{ end }}
xdebug.profiler_enable_trigger=On
xdebug.profiler_output_dir=/opt/profiling
xdebug.profiler_output_name=cachegrind.out.%t
');
		$debugDockerfile->run('apk add --no-cache $PHPIZE_DEPS');
		$debugDockerfile->run('docker-php-source extract');
		$debugDockerfile->run('pecl install xdebug'.$xdebugVersion);
		$debugDockerfile->run('docker-php-source delete');
		$debugDockerfile->run('docker-php-ext-enable xdebug');
		$debugDockerfile->run('apk del $PHPIZE_DEPS');

		return $debugDockerfile;
	}
}