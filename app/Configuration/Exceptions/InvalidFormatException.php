<?php namespace Rancherize\Configuration\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class InvalidFormatException
 * @package Rancherize\Configuration\Exceptions
 */
class InvalidFormatException extends Exception  {
	/**
	 * @var string
	 */
	private $expectedFormat;
	/**
	 * @var string
	 */
	private $path;
	/**
	 * @var string
	 */
	private $content;

	/**
	 * InvalidFormatException constructor.
	 * @param string $expectedFormat
	 * @param string $path
	 * @param string $content
	 * @param int $code
	 * @param \Exception $e
	 */
	public function __construct(string $expectedFormat, string $path, string $content, int $code = 0, \Exception $e = null) {
		parent::__construct("Failed to decode $path into $expectedFormat", $code, $e);
		$this->expectedFormat = $expectedFormat;
		$this->path = $path;
		$this->content = $content;
	}

	/**
	 * @return string
	 */
	public function getExpectedFormat(): string {
		return $this->expectedFormat;
	}

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}


}