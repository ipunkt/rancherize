<?php namespace Rancherize\Configuration\Writer;
use Rancherize\Configuration\Configuration;

/**
 * Interface Writer
 * @package Rancherize\Configuration\Writer
 *
 * Writes configuration files to the filesystem
 */
interface Writer {
	/**
	 * @param Configuration $configuration
	 * @param string $path
	 * @return mixed
	 */
	function write(Configuration $configuration, string $path);
}