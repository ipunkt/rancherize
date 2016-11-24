<?php namespace Rancherize\RancherAccess\Exceptions;
use Rancherize\Exceptions\Exception;

/**
 * Class RequestFailedException
 * @package Rancherize\RancherAccess\Exceptions
 *
 * thrown by the ApiService if a request was not successful
 */
class RequestFailedException extends Exception  {
	/**
	 * @var string
	 */
	private $url;
	/**
	 * @var array
	 */
	private $headers;

	/**
	 * RequestFailedException constructor.
	 * @param string $url
	 * @param array $headers
	 * @param int $httpcode
	 * @param \Exception $e
	 */
	public function __construct(string $url, array $headers, int $httpcode, \Exception $e = null) {
		$this->url = $url;
		$this->headers = $headers;
		parent::__construct("Query to $url failed: $httpcode", $httpcode, $e);
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @return array
	 */
	public function getHeaders(): array {
		return $this->headers;
	}


}