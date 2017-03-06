<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\General\Services\ByKeyService;

/**
 * Class ServiceParserV1
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class ServiceParserV1 implements ServiceParser {
	/**
	 * @var ByKeyService
	 */
	private $byKeyService;

	/**
	 * ServiceParserV1 constructor.
	 * @param ByKeyService $byKeyService
	 */
	public function __construct(ByKeyService $byKeyService) {
		$this->byKeyService = $byKeyService;
	}

	/**
	 * @param string $serviceName
	 * @param array $data
	 * @return mixed
	 */
	public function parse(string $serviceName, array $data) {
		list($key, $service) = $this->byKeyService->byKey($serviceName, $data);

		return $service;
	}
}