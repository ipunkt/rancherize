<?php namespace Rancherize\Blueprint\NginxSnippets\NginxSnippetExtraInformation;

use Rancherize\Blueprint\Infrastructure\Service\ServiceExtraInformation;

/**
 * Interface NginxSnippetExtraInformation
 * @package Rancherize\Blueprint\NginxSnippets\NginxSnippetExtraInformation
 */
class NginxSnippetExtraInformation implements ServiceExtraInformation {

	const IDENTIFIER = 'nginx-snippet';

	/**
	 * @var array
	 */
	protected $snippets = [];

	/**
	 * @var bool
	 */
	protected $mountWorkdir = false;

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return self::IDENTIFIER;
	}

	/**
	 * @param $path
	 */
	public function addSnippet( $path ) {
		$this->snippets[$path] = $path;
	}

	/**
	 * @return array
	 */
	public function getSnippets(): array {
		return $this->snippets;
	}

	/**
	 * @return bool
	 */
	public function isMountWorkdir(): bool {
		return $this->mountWorkdir;
	}

	/**
	 * @param bool $mountWorkdir
	 */
	public function setMountWorkdir( bool $mountWorkdir ) {
		$this->mountWorkdir = $mountWorkdir;
	}
}