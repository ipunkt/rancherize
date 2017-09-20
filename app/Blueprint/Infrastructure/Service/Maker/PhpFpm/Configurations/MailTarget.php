<?php

/**
 * Interface MailTarget
 */
interface MailTarget {

	/**
	 * @param string $host
	 */
	function setMailHost(string $host);

	/**
	 * @param int $port
	 */
	function setMailPort(int $port);

	/**
	 * @param string $username
	 */
	function setMailUsername(string $username);

	/**
	 * @param string $password
	 */
	function setMailPassword(string $password);

	/**
	 * @param string $authMethod
	 */
	function setMailAuthentication(string $authMethod);
}