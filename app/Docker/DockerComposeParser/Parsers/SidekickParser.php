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
	 * @var ByKeyService
	 */
	private $byKeyService;

	/**
	 * SidekickParser constructor.
	 * @param SidekickNameParser $nameParser
	 * @param ByKeyService $byKeyService
	 */
	public function __construct(SidekickNameParser $nameParser, ByKeyService $byKeyService) {
		$this->nameParser = $nameParser;
		$this->byKeyService = $byKeyService;
	}

	public function parseSidekicks($serviceName, array $service, array $services) {
		$names = $this->nameParser->parseNames($serviceName, $service);

		$sidekicks = [];
		foreach($names as $name) {
			$sidekick = $this->byKeyService->byKey($name, $services);
			$sidekicks[] = $sidekick;
		}

		return $sidekicks;
	}
}