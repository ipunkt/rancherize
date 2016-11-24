<?php namespace Rancherize\RancherAccess\ApiService;

/**
 * Interface ApiService
 * @package Rancherize\RancherAccess\ApiService
 *
 * Abstract access to an api
 */
interface ApiService {

	/**
	 * Do a get request to retrieve infos from the api
	 *
	 * @param string $url
	 * @param array $headers
	 * @param array $acceptedSuccess defaults to [200]
	 * @return mixed
	 */
	function get(string $url, array $headers = [], array $acceptedSuccess = null);

	/**
	 * Do a post request to send data to the api
	 *
	 * @param string $url
	 * @param string $content
	 * @param array $headers
	 * @param array $acceptedSuccess defaults to [204]
	 * @return mixed
	 */
	function post(string $url, string $content, array $headers = [], array $acceptedSuccess = null);
}