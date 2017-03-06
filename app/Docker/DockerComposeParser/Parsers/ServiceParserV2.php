<?php namespace Rancherize\Docker\DockerComposeParser\Parsers;

use Rancherize\General\Exceptions\KeyNotFoundException;
use Rancherize\General\Services\ByKeyService;

/**
 * Class ServiceParserV2
 * @package Rancherize\Docker\DockerComposeParser\Parsers
 */
class ServiceParserV2 implements ServiceParser {

	/**
	 * @var ByKeyService
	 */
	private $byKeyService;

	/**
	 * ServiceParserV2 constructor.
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
		if(!array_key_exists('services', $data))
			throw new KeyNotFoundException('services', array_keys($data));

		$services = $data['services'];

		list($key, $service) = $this->byKeyService->byKey($serviceName, $services);

		return $service;
	}
}