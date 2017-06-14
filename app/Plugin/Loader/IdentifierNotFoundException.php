<?php namespace Rancherize\Plugin\Loader;

/**
 * Class IdentifierNotFoundException
 * @package Rancherize\Plugin\Loader
 */
class IdentifierNotFoundException extends \RuntimeException {
	/**
	 * @var string
	 */
	private $identifier;

	/**
	 * IdentifierNotFoundException constructor.
	 * @param string $identifier
	 * @param int $message
	 * @param int $code
	 * @param \Exception|null $e
	 */
	public function __construct($identifier, $message, $code = 0, \Exception $e = null) {
		$this->identifier = $identifier;
		parent::__construct($message, $code, $e);
	}

	/**
	 * @return string
	 */
	public function getIdentifier(): string {
		return $this->identifier;
	}

}