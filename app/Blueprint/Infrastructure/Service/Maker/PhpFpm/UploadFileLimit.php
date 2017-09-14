<?php namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm;

/**
 * Interface UploadFileLimit
 * @package Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm
 */
interface UploadFileLimit {

	const DEFAULT_UPLOAD_FILE_LIMIT = 'default';

	/**
	 * @return $this
	 */
	function setUploadFileLimit($limit);

}