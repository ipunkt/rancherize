<?php namespace Rancherize\RancherAccess\ApiService;
use Rancherize\RancherAccess\Exceptions\RequestFailedException;

/**
 * Class CurlApiService
 * @package Rancherize\RancherAccess\ApiService
 *
 * Uses the php curl interface to provide the ApiService
 */
class CurlApiService implements ApiService {

	/**
	 * @param string $url
	 * @param array $headers
	 * @param array $acceptedSuccess
	 * @return mixed
	 */
	public function get(string $url, array $headers = [], array $acceptedSuccess = null) {
		if($acceptedSuccess === null)
			$acceptedSuccess = [200];

		$curlHandler = curl_init($url);

		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

		$curlHeader = [];
		foreach($headers as $header => $value)
			$curlHeader[] = "$header: $value";

		curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $curlHeader);

		$data = curl_exec($curlHandler);

		$httpcode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);

		if( !in_array($httpcode, $acceptedSuccess) )
			throw new RequestFailedException($url, $headers, $httpcode);

		return $data;
	}

	/**
	 * @param string $url
	 * @param string $content
	 * @param array $headers
	 * @param array $acceptedSuccess
	 * @return mixed
	 */
	public function post(string $url, string $content, array $headers = [], array $acceptedSuccess = null) {
		if($acceptedSuccess === null)
			$acceptedSuccess = [201, 204];

		$curlHandler = curl_init($url);

		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandler, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $content);

		$curlHeader = [];
		foreach($headers as $header => $value)
			$curlHeader[] = "$header: $value";

		curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $curlHeader);

		$data = curl_exec($curlHandler);

		$httpcode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);

		if( !in_array($httpcode, $acceptedSuccess) )
			throw new RequestFailedException($url, $headers, $httpcode);

		return $data;
	}
}