<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\UploadFileLimit;

trait UploadFileLimitTrait {


	/**
	 * @var string
	 */
	private $uploadFileLimit = UploadFileLimit::DEFAULT_UPLOAD_FILE_LIMIT;

	/**
	 * @return $this
	 */
	public function setUploadFileLimit( $limit ) {
		$this->uploadFileLimit = $limit;
		return $this;
	}
}