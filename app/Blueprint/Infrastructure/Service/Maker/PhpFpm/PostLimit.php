<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

/**
 * Interface PostLimit
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface PostLimit {

	const DEFAULT_POST_LIMIT = 'default';

	/**
	 * @return $this
	 */
	function setPostLimit($limit);
}