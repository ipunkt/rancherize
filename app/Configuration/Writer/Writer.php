<?php namespace Rancherize\Configuration\Writer;
use Rancherize\Configuration\Configuration;

/**
 * Interface Writer
 * @package Rancherize\Configuration\Writer
 */
interface Writer {
	/**
	 * @param Configuration $configuration
	 * @param string $path
	 * @return mixed
	 */
	function write(Configuration $configuration, string $path);
}