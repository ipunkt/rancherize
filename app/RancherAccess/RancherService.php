<?php namespace Rancherize\RancherAccess;
use Rancherize\RancherAccess\ApiService\ApiService;
use Rancherize\RancherAccess\Exceptions\StackNotFoundException;
use ZipArchive;

/**
 * Class RancherService
 * @package Rancherize\RancherAccess
 */
class RancherService {
	/**
	 * @var RancherAccount
	 */
	private $account;
	/**
	 * @var ApiService
	 */
	private $apiService;

	/**
	 * RancherService constructor.
	 * @param ApiService $apiService
	 * @param RancherAccount $account
	 */
	public function __construct(ApiService $apiService, RancherAccount $account = null) {
		$this->account = $account;
		$this->apiService = $apiService;
	}

	/**
	 * @param RancherAccount $account
	 */
	public function setAccount(RancherAccount $account) {
		$this->account = $account;
		return $this;
	}

	/**
	 * @param string $stackName
	 * @return string
	 */
	public function retrieveConfig(string $stackName) : array {
		
		$stackId = $this->getStackIdByName($stackName);
		
		$url = implode('/', [
			$this->account->getUrl(),
			'environments',
			$stackId,
			'?action=exportconfig'
		]);
		
		$headers = [];
		$this->addAuthHeader($headers);
		
		$jsonContent = json_encode([
			'serviceIds' => []
		]);
		$data = $this->apiService->post($url, $jsonContent, $headers, [200]);
		
		$decodedData = json_decode($data, true);
		$dockerCompose = $decodedData['dockerComposeConfig'];
		$rancherCompose = $decodedData['rancherComposeConfig'];
		
		// Empty files are not sent empty so we force them to be
		if(substr($dockerCompose, 0, 2) === '{}')
			$dockerCompose = '';
		if(substr($rancherCompose, 0, 2) === '{}')
			$rancherCompose = '';
		
		return [$dockerCompose, $rancherCompose];
	}

	/**
	 * @param $stackName
	 * @return string
	 */
	private function getStackIdByName($stackName) {
		$url = implode('/', [
			$this->account->getUrl(),
			'environments'
		]);
		
		$headers = [];
		$this->addAuthHeader($headers);
		
		$jsonData = $this->apiService->get($url, $headers);
		$data = json_decode($jsonData, true);
		
		foreach($data['data'] as $stack) {
			if(strtolower($stack['name']) === strtolower($stackName) )
				return $stack['id'];
		}
		
		throw new StackNotFoundException($stackName);
	}

	/**
	 * @return array
	 */
	protected function addAuthHeader(&$headers) {
		$user = $this->account->getKey();
		$password = $this->account->getSecret();

		$headers['Authorization'] = 'Basic ' . base64_encode("$user:$password");
	}

	/**
	 * @param string $stackName
	 */
	public function createStack(string $stackName, $dockerCompose = null, $rancherCompose = null) {
		if($dockerCompose === null)
			$dockerCompose = '';
		if($rancherCompose === null)
			$rancherCompose = '';
		
		$url = implode('/', [
			$this->account->getUrl(),
			'environments'
		]);
		
		$headers = [];
		$this->addAuthHeader($headers);
		
		$jsonContent = json_encode([
			'name' => $stackName,
			'dockerCompose' => $dockerCompose,
			'rancherCompose' => $rancherCompose,
		]);
		
		$headers['Content-Type'] = 'application/json';
		$headers['Content-Length'] = strlen($jsonContent);
		
		$this->apiService->post($url, $jsonContent, $headers);
	}
}