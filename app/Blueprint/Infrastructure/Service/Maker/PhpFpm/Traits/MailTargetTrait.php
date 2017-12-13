<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


trait MailTargetTrait {

	/**
	 * @var string
	 */
	private $mailHost;

	/**
	 * @var int
	 */
	private $mailPort;

	/**
	 * @var string
	 */
	private $mailUsername;

	/**
	 * @var string
	 */
	private $mailPassword;

	/**
	 * @var string
	 */
	private $mailAuthentication;

	/**
	 * @param string $host
	 */
	public function setMailHost( string $host ) {
		$this->mailHost = $host;
	}

	/**
	 * @param int $port
	 */
	public function setMailPort( int $port ) {
		$this->mailPort = $port;
	}

	/**
	 * @param string $username
	 */
	public function setMailUsername( string $username ) {
		$this->mailUsername = $username;
	}

	/**
	 * @param string $password
	 */
	public function setMailPassword( string $password ) {
		$this->mailPassword = $password;
	}

	/**
	 * @param string $authMethod
	 */
	public function setMailAuthentication( string $authMethod ) {
		$this->mailAuthentication = $authMethod;
	}

}