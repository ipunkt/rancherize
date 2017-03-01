<?php namespace Rancherize\Docker\RancherComposeReader;

use Symfony\Component\Yaml\Yaml;

/**
 * Class RancherComposeReader
 * @package Rancherize\Docker\RancherComposeReader
 */
class RancherComposeReader {

	/**
	 * @param $fileContent
	 * @return array
	 */
	public function read($fileContent) {
		return Yaml::parse($fileContent);
	}

}