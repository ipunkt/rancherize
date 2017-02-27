<?php namespace Rancherize\Docker\DockerComposeParser;
use Rancherize\Docker\Exceptions\DockerException;

/**
 * Class NotFoundException
 * @package Rancherize\Docker\DockerComposeParser
 */
class NotFoundException extends DockerException {
	/**
	 * @var string
	 */
	private $type;
	/**
	 * @var string
	 */
	private $stackName;
	/**
	 * @var array
	 */
	private $data;

	/**
	 * NotFoundException constructor.
	 * @param string $type
	 * @param string $stackName
	 * @param array $data
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct($type, $stackName, array $data, int $code = 0, \Exception $e = null) {
		$this->type = $type;
		$this->stackName = $stackName;
		$this->data = $data;
		$values = array_keys($data);

		parent::__construct("$type with name $stackName not found. Available values: $values", $code, $e);
	}
}