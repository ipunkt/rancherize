<?php namespace Rancherize\RancherAccess\NameMatcher;

/**
 * Class CompleteNameMatcher
 * @package Rancherize\RancherAccess\NameMatcher
 */
class CompleteNameMatcher implements NameMatcher {
	/**
	 * @var string
	 */
	private $matchName;

	/**
	 * CompleteNameMatcher constructor.
	 * @param string $matchName
	 */
	public function __construct( string $matchName) {
		$this->matchName = $matchName;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function match( string $name ) {
		return ($this->matchName === $name);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->matchName;
	}
}