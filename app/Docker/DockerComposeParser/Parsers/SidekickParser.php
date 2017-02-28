<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\General\Services\ByKeyService;

/**
 * Class SidekickParser
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class SidekickParser {
	/**
	 * @var SidekickNameParser
	 */
	private $nameParser;

	/**
	 * @var ServiceParser
	 */
	private $serviceParser;

	/**
	 * SidekickParser constructor.
	 * @param SidekickNameParser $nameParser
	 * @param ServiceParser $serviceParser
	 */
	public function __construct(SidekickNameParser $nameParser, ServiceParser $serviceParser) {
		$this->nameParser = $nameParser;
		$this->serviceParser = $serviceParser;
	}

	public function parseSidekicks($serviceName, array $service, array $services) {
		$names = $this->nameParser->parseNames($serviceName, $service);

		$sidekicks = [];
		foreach($names as $name) {
			$sidekick = $this->serviceParser->parse($name, $services);
			$sidekicks[] = $sidekick;
		}

		return $sidekicks;
	}
}