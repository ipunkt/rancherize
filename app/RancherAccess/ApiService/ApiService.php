<?php namespace Rancherize\RancherAccess\ApiService;

/**
 * Interface ApiService
 * @package Rancherize\RancherAccess\ApiService
 */
interface ApiService {

	/**
	 * @param string $url
	 * @param array $headers
	 * @param array $acceptedSuccess defaults to [200]
	 * @return mixed
	 */
	function get(string $url, array $headers = [], array $acceptedSuccess = null);

	/**
	 * @param string $url
	 * @param array $content
	 * @param array $headers
	 * @param array $acceptedSuccess defaults to [204]
	 * @return mixed
	 */
	function post(string $url, string $content, array $headers = [], array $acceptedSuccess = null);
}