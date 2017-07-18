<?php namespace Rancherize\Services\PathService;

/**
 * Class Path
 * @package Rancherize\Services\PathService
 */
class Path {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $filename;

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return Path
	 */
	public function setPath( string $path ): Path {
		$this->path = $path;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilename(): string {
		return $this->filename;
	}

	/**
	 * @param string $filename
	 * @return Path
	 */
	public function setFilename( string $filename ): Path {
		$this->filename = $filename;
		return $this;
	}


}