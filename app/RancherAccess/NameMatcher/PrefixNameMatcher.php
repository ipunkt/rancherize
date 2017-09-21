<?php namespace Rancherize\RancherAccess\NameMatcher;

/**
 * Class PrefixNameMatcher
 * @package Rancherize\RancherAccess\NameMatcher
 */
class PrefixNameMatcher implements NameMatcher {
	/**
	 * @var
	 */
	private $matchName;

	/**
	 * PrefixNameMatcher constructor.
	 * @param $matchName
	 */
	public function __construct( $matchName ) {
		$this->matchName = $matchName;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function match( string $name ) {
		return ($serviceNameContainsName = strpos($name, $this->matchName) !== false);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->matchName;
	}
}